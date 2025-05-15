<?php

use App\Imports\CommonImport;
use App\Traits\EncryptDecryptTrait;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

/**********************************************************
 * return :Success Response
 * developer : Ravi Tewatia
 **********************************************************/
if (!function_exists('successResponse')) {
    function successResponse($statusCode, $message = "", $result = [], $otherData = [])
    {
        $response = [
            "status" => $statusCode,
            "message" => $message,
            "result" => $result,
        ];
        if ($otherData) {
            $response['other_data'] = $otherData;
        }
        return $response;
    }

}

/**********************************************************
 * return : Error Response
 * developer : Ravi Tewatia
 **********************************************************/
if (!function_exists('errorResponse')) {
    function errorResponse($statusCode, $message, $result = [])
    {
        return [
            "status" => $statusCode,
            "message" => $message,
            "result" => $result,
        ];
    }
}

/**********************************************************
 * return : Error Response
 * developer : Ravi Tewatia
 **********************************************************/
if (!function_exists('catchResponse')) {
    function catchResponse($statusCode, $message = "", $result = [])
    {
        return [
            "status" => $statusCode,
            "message" => $message,
            "result" => $result,
        ];
    }
}

/**********************************************************
 * return : Validation Response
 * developer : Ravi Tewatia
 **********************************************************/
if (!function_exists('validationResponse')) {
    function validationResponse($statusCode, $message, $result)
    {
        return response()->json(["message" => $message, "result" => $result], $statusCode);
    }
}

/**********************************************************
 * return : Error Response
 * developer : Ravi Tewatia
 **********************************************************/
if (!function_exists('finalResponse')) {
    function finalResponse($result)
    {
        return response()->json($result, $result['status']);
    }
}

/**********************************************************
 * return : return slug for used in static method and others
 * developer : Ravi Tewatia
 **********************************************************/
if (!function_exists('slug')) {
    function slug($digit = 12)
    {
        $slug = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $digit);
        return $slug;
    }
}

/**********************************************************
 * return : return array from excel file
 * developer : Ravi Tewatia
 **********************************************************/
if (!function_exists('excelToArray')) {
    function excelToArray($file)
    {
        return Excel::toArray(new CommonImport, $file);
    }
}

/**********************************************************
 * return : encrypted value
 * developer : Ravi Tewatia
 **********************************************************/
if (!function_exists('encryptData')) {
    function encryptData($value)
    {
        return EncryptDecryptTrait::encryptData($value);
    }
}

/**********************************************************
 * return : decrypted value
 * developer : Ravi Tewatia
 **********************************************************/
if (!function_exists('decryptData')) {
    function decryptData($value)
    {
        return EncryptDecryptTrait::decryptData($value);
    }
}

#--------------------------------------------------------#
# Function : getKeyBySlug                                #
# Model Used: No model used.work with DB facade          #
# Action : key from table by slug                        #
# Returns : value of given key by slug                   #
# Return Type : int/string                               #
# Return To: respected controller                        #
# Developer : Ravi Tewatia                              #
#--------------------------------------------------------#
if (!function_exists('getKeyBySlug')) {
    function getKeyBySlug($tableName = '', $key = '', $slug = '', $id = '', $column = '', $statusId = true)
    {
        if (!empty($slug) || (!empty($id) && !empty($column))) {
            $qry = DB::table($tableName);
            if ($statusId) {
                $qry->where('status_id', '<>', config('constants.STATUS_DELETE'));
            }
            if (!empty($slug)) {
                $qry->where('slug', $slug);
            }
            if (!empty($id)) {
                if (!empty($column)) {
                    $qry->where($column, $id);
                }
            }
            $getKey = $qry->select($key)->first();
            $getId = null;
            if (isset($getKey) && !empty($getKey)) {
                $getId = $getKey->$key;
            }
            return $getId;
        } else {
            return 0;
        }
    }

}
