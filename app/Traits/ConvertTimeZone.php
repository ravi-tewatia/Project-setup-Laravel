<?php

namespace App\Traits;

trait ConvertTimeZone
{

    /**********************************************************
     * return : Convert user timezon to UTC dynamiclly.
     * developer : Ravi
     **********************************************************/
    public static function convertUserTimezoneToUtc($datetime, $format = "Y-m-d H:i:s", $to = "UTC")
    {
        $from = date_default_timezone_get();
        $UTC = new \DateTimeZone($from);
        $newTZ = new \DateTimeZone($to);
        $date = new \DateTime($datetime, $UTC);
        $date->setTimezone($newTZ);
        return $date->format($format);
    }

    /**********************************************************
     * return : Convert from UTC to user timezon dynamiclly.
     * developer : Ravi
     **********************************************************/
    public static function convertUtcToUserTimezone($datetime, $format = "Y-m-d H:i:s", $from = "UTC")
    {
        $to = date_default_timezone_get();
        $UTC = new \DateTimeZone($from);
        $newTZ = new \DateTimeZone($to);
        $date = new \DateTime($datetime, $UTC);
        $date->setTimezone($newTZ);
        return $date->format($format);
    }

    /**********************************************************
     * return : Convert timezon manually by passing from and to timezon name.
     * developer : Ravi
     **********************************************************/
    public static function convertTimezone($datetime, $format = "Y-m-d H:i:s", $from = "UTC", $to = "Australia/Brisbane")
    {
        $UTC = new \DateTimeZone($from);
        $newTZ = new \DateTimeZone($to);
        $date = new \DateTime($datetime, $UTC);
        $date->setTimezone($newTZ);
        return $date->format($format);
    }
}
