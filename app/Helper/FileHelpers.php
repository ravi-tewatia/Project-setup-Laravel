<?php

namespace App\Helper;

use App\Helper\S3Helpers;
use File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileHelpers
{
    /* This function is used to Upload file To Storage Folder */
    public static function fileUpload($image, $localFolder = '')
    {
        $orgfileName = $image->getClientOriginalName();
        $ext = $image->getClientOriginalExtension();
        $realPath = $image->getRealPath();
        $size = $image->getSize();
        $type = $image->getMimeType();
        $fileName = $orgfileName . '_' . time() . '.' . $ext;

        if (!File::isDirectory($localFolder)) {
            File::makeDirectory($localFolder, 0777, true, true);
        }

        $ImageFilePath = Storage::path($localFolder);
        if ($image->move($ImageFilePath, $fileName)) {
            $status = 200;
        } else {
            $status = 400;
        }

        return [
            'status' => $status,
            'orgFileName' => $orgfileName,
            'ext' => $ext,
            'realPath' => $realPath,
            'size' => $size,
            'type' => $type,
            'fileName' => $fileName,
        ];
    }

    /*This Function is Used to Check the validation */
    public static function checkValidation($validations, $fileObject, $fieldName)
    {
        $errors = [];

        if (!empty($validations)) {
            $size = $fileObject->getSize();
            $fileType = $fileObject->getExtension();
            $allowedType = $validations['allowedType'];
            $sizeFormat = $validations['maxFileSizeType'];
            $requestSize = $validations['maxFileSize'];
            $typeArray = explode(',', $allowedType);
            $mimeType = $fileObject->getMimeType();

            if (strtolower($sizeFormat) == 'kb') {
                $size = ((int) $size / 1000);
            } else if (strtolower($sizeFormat) == 'mb') {
                $size = ((int) $size / 1000000);
            }

            if ($size > $requestSize) {
                $errors['field'] = $fieldName;
                $errors['message'][] = 'File size exceeds maximum limit.';
            }

            if (!in_array($mimeType, $typeArray)) {
                $errors['field'] = $fieldName;
                $errors['message'][] = 'File Format Not Supported.';
            }
        }
        return $errors;
    }

    /* This Function is Used To Prepare Available file Data For Validation */
    public static function prepareForValidation($availableFiles)
    {
        $fileErrors = [];
        $filesData = [];
        $resizefiles = [];
        if (!empty($availableFiles)) {

            foreach ($availableFiles as $key => $value) {

                if (!empty($value['filesData'])) {
                    foreach ($value['filesData'] as $file) {
                        /* Get Image From S3 :: START */
                        $imageData = S3Helpers::getImages($file['fileDestination'], $file['newName']);
                        FileHelpers::saveFileToLocal('temp_images', $imageData, $file['newName']);

                        $localUrl = FileHelpers::getUploadedFileUrl('temp_images', $file['newName']);
                        $fileObject = FileHelpers::createFileObject($localUrl);
                        $errors = FileHelpers::checkValidation($value['validationRules'], $fileObject, $value['fieldName']);

                        if (!empty($errors)) {
                            $fileErrors[$value['fieldName']]['message'] = $errors['message'];
                        }
                        if (!empty($value['resizeOptions'])) {
                            $resizefiles[] = $value;
                        }

                        FileHelpers::deleteLocalImage('temp_images', $file['newName']);
                    }
                    $filesData[] = array(
                        'fieldName' => $value['fieldName'],
                        'uploadedTo' => $value['uploadedTo'],
                        'resizeOptions' => $value['resizeOptions'],
                        'filesData' => $value['filesData']
                    );
                }
            }
        }

        if (!empty($fileErrors)) {
            $fileErrors['status'] = false;
            return $fileErrors;
        } else {
            $filesData['status'] = true;
            $filesData['resizeFiles'] = $resizefiles;
            return $filesData;
        }
    }

    /* This Function is Used To Save File To local Folder */
    public static function saveFileToLocal($folderName, $imageRawData, $fileName)
    {

        if (!File::isDirectory($folderName)) {
            File::makeDirectory($folderName, 0777, true, true);
        }

        $localPath = Storage::disk('public');
        $local = $folderName . '/' . $fileName;
        $localPath->put($local, file_get_contents($imageRawData), 'public');
    }

    /* This Function is Used to Get Uploaded File Url */
    public static function getUploadedFileUrl($folderName, $fileName)
    {
        return Storage::path($folderName . '/' . $fileName);
    }

    /* This Function is Used to CreateFileobjct From Url */
    public static function createFileObject($url)
    {

        $path_parts = pathinfo($url);

        $newPath = $path_parts['dirname'] . '/tmp-files/';
        if (!is_dir($newPath)) {
            mkdir($newPath, 0777);
        }

        $newUrl = $newPath . $path_parts['basename'];
        copy($url, $newUrl);
        $imgInfo = getimagesize($newUrl);

        $file = new UploadedFile(
            $newUrl,
            $path_parts['basename'],
            $imgInfo['mime'],
            filesize($url),
            true,
            true
        );

        return $file;
    }

    /* This Function is Used to Delete Local image From Folder */
    public static function deleteLocalImage($folderName, $fileName)
    {
        Storage::delete($folderName . '/' . $fileName);
        Storage::deleteDirectory('temp_images/tmp-files');
    }

    /* This Function is Used to Resize the Image */
    public static function resizeImage($params = [])
    {
        $folder = $params['folder'];
        $img = $params['img'];
        $height = isset($params['height']) ? $params['height'] : '100';
        $width = isset($params['width']) ? $params['width'] : '100';
        $s3folder = $params['s3Folder'];
        $uploadedTo = $params['uploadedTo'];

        $orgName = $params['orgName'];
        $filePath = Storage::path($folder);

        if (!File::isDirectory($filePath)) {
            File::makeDirectory($filePath, 0777, true, true);
        }

        $upload = $img->resize($height, $width, function ($const) {
            $const->aspectRatio();
        })->save($filePath . '/' . $orgName);

        /* For s3 Upload :: START */
        $s3filePath = $s3folder;
        S3Helpers::saveImage($filePath, $s3filePath, $orgName, $uploadedTo);
        /* For s3 Upload :: END */

        if ($upload) {
            return 200;
        } else {
            return 400;
        }
    }

    /*this function is used to delete files */
    public static function deleteFiles($deletedFiles)
    {

        $s3Images = [];
        $filesData = [];
        foreach ($deletedFiles as $value) {

            $fileName =  $value['newName'];
            $folderName = $value['fileDestination'];
            $image_path = $folderName . '/' . $fileName;

            // $s3Path = Storage::disk('s3')->url($image_path);
            if ($value['uploadedTo'] == 's3') {
                $s3Images[] = $image_path;
            } else {
                FileHelpers::deleteLocalImage($folderName, $fileName);
            }


            $filesData[] = $value;
        }

        if (!empty($s3Images)) {
            S3Helpers::deleteS3Image($s3Images);
        }

        return $filesData;
    }
}
