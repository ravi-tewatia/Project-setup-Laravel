<?php

namespace App\Traits;

use Carbon\Carbon;

trait DateFormatTrait
{
    public function mysqlDateFormat($data, $fieldName)
    {
        $result = '';
        if (isset($data[$fieldName]) && !empty($data[$fieldName])) {
            $result = Carbon::parse($data[$fieldName])->format(config('constants.PHP_DATE_FORMAT'));
        } else {
            $result = null;
        }
        return $result;
    }
    public function phpDateFormat($data, $fieldName)
    {
        $result = '';
        if (isset($data[$fieldName]) && !empty($data[$fieldName])) {
            $result = Carbon::parse($data[$fieldName])->format(config('constants.DISPLAY_DATE_FORMAT'));
        } else {
            $result = null;
        }
        return $result;
    }

    public static function dateConvert($date, $format = 'Y-m-d')
    {
        return date($format, strtotime($date));
    }
}
