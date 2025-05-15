<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

class EncryptDecryptTrait
{

    /**
     * Encrypt data first AES encrypt and then base 64 with ecrypt key
     * @param $data
     * @param $key
     *
     * @return bool|string
     */
    public static function encryptData($value)
    {
        $key = env('ENCRYPT_KEY');
        $encKey = DB::select("SELECT to_base64(AES_ENCRYPT('$value','$key')) AS slug")[0]->slug;
        return $encKey;
    }

    /**
     * Decrypt data first base 64 then AES encrypt by Decrypt key
     * @param $value
     * @param $key
     *
     * @return bool|string
     */
    public static function decryptData($value)
    {
        $key = env('ENCRYPT_KEY');
        $decKey = DB::select("SELECT AES_DECRYPT(from_base64('$value'), '$key') AS slug")[0]->slug;
        return $decKey;
    }
}
