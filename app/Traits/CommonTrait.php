<?php

namespace App\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait CommonTrait
{

    public static function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        return $data;
    }

    /**
     * used for extraxt array for variable
     * Ravi Tewatia
     */
    public static function underscoreToCamelCase($array, $capitalizeFirstCharacter = false)
    {

        $arrayKeys = array_keys($array);
        $arrayValues = array_values($array);
        foreach ($arrayKeys as $key => $value) {
            if ($capitalizeFirstCharacter) {
                $newKey[] = str_replace(' ', '', ucwords(str_replace('_', ' ', $value)));
            } else {
                $newKey[] = str_replace(' ', '', lcfirst(ucwords(str_replace('_', ' ', $value))));
            }
        }
        $newArray = array_combine($newKey, $arrayValues);
        return $newArray;
    }

    public static function decode($value)
    {
        return urldecode(stripslashes($value));
    }
}
