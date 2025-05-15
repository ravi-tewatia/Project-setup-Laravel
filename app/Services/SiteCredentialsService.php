<?php
namespace App\Services;

use Aws\Ssm\SsmClient;
use Illuminate\Support\Facades\Redis;

/**
 * It fetches credentials using AWS services
 * Most of the methods and variables are defined as static
 * as the class performs all stuff by itself
 * Some object dependent methods are implemented as
 * need to access credentials throughout the entire application
 */
class SiteCredentialsService
{

    //s3 object, used in class for internal purposes
    public static $cacheModel;
    private static $s3_instance;

    //this array contains keys to fetch from S3
    public static $credential_keys = array(
        'local_db_host',
        'local_db_database',
        'local_db_password',
        'local_db_username',
        'local_redis_prefix',
    );

    /**
     * This array can be modified for the access
     * Used static as this class is called two times
     * first on before application config & second
     * on component initialization
     * Cannot modify out of the class
     */
    private static $credentials = array();

    public function __construct($config = array())
    {
        try {
            //s3 instance will be created everytime as may need in future
            self::$s3_instance = new SsmClient([
                'version' => 'latest',
                'region' => env('AWS_REGION', 'ap-southeast-1'),
                'credentials' => array(
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ),
            ]);
            self::boot();
        } catch (\Exception $ex) {
            self::handleException($ex);
        }
    }

    private static function buildCache()
    {
        $file_content = array();
        array_walk(self::$credentials, function ($v, $k) use (&$file_content) {
            $file_content[$k] = $v;
        });
        return Redis::set('cache_set', json_encode($file_content));
    }

    private static function fetchCredentials()
    {
        try {
            $splitted_credentials = array_chunk(self::$credential_keys, 10);
            if ($splitted_credentials) {
                foreach ($splitted_credentials as $credential_group) {
                    $result = self::$s3_instance->getParameters([
                        'Names' => $credential_group,
                        'WithDecryption' => true,
                    ]);
                    $keys = $result->toArray();

                    if (!empty($keys['Parameters'])) {
                        array_walk($keys['Parameters'], function ($v) {
                            self::$credentials[$v['Name']] = self::_encrypt($v['Value']);
                        });
                    }
                }
                return self::buildCache();
            }
        } catch (\Exception $ex) {
            self::handleException($ex);
        }
    }

    private static function handleException($ex = false)
    {
        echo $ex->getMessage() . "<br/><br/>Something went wrong! Please contact site admin.";
        exit;
    }

    public static function boot()
    {
        /* START : WITH REDIS IMPLEMENT */
        $nocache = true;
        $arList = Redis::keys('cache_set');
        if ($arList) {
            $secrets = Redis::get('cache_set');
            if ($credentials = json_decode($secrets, true)) {
                /**
                 * if content in the file is not in valid JSON form
                 * then the system will recreate the cache file
                 */
                $nocache = false;
                array_walk($credentials, function ($v, $k) {
                    self::$credentials[$k] = $v;
                });
            }
        } else {
            self::fetchCredentials();
        }
        /* END :  WITH REDIS IMPLEMENT */
    }

    public function getAllCredential()
    {
        return self::$credentials;
    }

    public function getCredential($key)
    {
        if (isset($key)) {
            $radisValue = Redis::get($key);
            return self::_decrypt($radisValue);
        }
    }

    public function rebuildCache()
    {
        //re-fetching all credentials from AWS and building cache
        return self::fetchCredentials();
    }

    public static function _encrypt($str = '')
    {
        if ($str != '') {
            return base64_encode(openssl_encrypt($str, "aes-256-cbc", "lr5JLRrVoT", true, str_repeat(chr(0), 16)));
        } else {
            return '';
        }
    }

    public static function _decrypt($en_str = '')
    {
        $en_str = trim($en_str);
        if ($en_str != '') {
            return openssl_decrypt(base64_decode($en_str), "aes-256-cbc", "lr5JLRrVoT", true, str_repeat(chr(0), 16));
        } else {
            return '';
        }
    }

    public static function removeallcache()
    {
        return Redis::flushAll();
    }

    public static function exprediscache($key, $time = 1)
    {
        if (!empty($key)) {
            return Redis::expire($key, $time);
        }
    }
}
