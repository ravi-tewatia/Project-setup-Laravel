<?php

namespace  App\Helper;

use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;

class S3Helpers
{
    /* This Function is Used To get Image from S3 */
    public static function getImages($destination, $image)
    {
        $image_path = $destination . '/' . $image;

        $exists = Storage::disk('s3')->exists($image_path);
        $image = ($exists) ? Storage::disk('s3')->url($image_path) : '';

        return $image;
    }

    /* This Function is Used To Save Image To S3*/
    public static function saveImage($localDestination, $s3destination, $name, $uploadedTo = '')
    {
        $s3 = Storage::disk('s3');
        $s3filePath = $s3destination . $name;
        if ($uploadedTo == 's3') {
            if ($s3->put($s3filePath, file_get_contents($localDestination . '/' . $name), 'public')) {
                Storage::delete($localDestination . '/' . $name);
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /* this function is used to Delete image from S3 */
    public static function deleteS3Image($file_path)
    {
        Storage::disk('s3')->delete($file_path);
    }
}
