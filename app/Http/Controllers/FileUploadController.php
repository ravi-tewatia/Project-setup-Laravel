<?php

namespace App\Http\Controllers;

use App\Helper\FileHelpers;
use App\Helper\S3Helpers;
use File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Jobs\ProcessImages;

class FileUploadController extends Controller
{
    /* this function include  processing of request for validation */
    public function submit(Request $request)
    {

        try {

            $availableFilesJson = $request->get('availableFiles') ? $request->get('availableFiles') : '{}';

            $availableFiles = json_decode($availableFilesJson, true);

            /* for deleted files json code start */
            $deletedFilesJson = $request->get('deletedFiles') ? $request->get('deletedFiles') : '{}';
            $deletedFiles = json_decode($deletedFilesJson, true);
            if (!empty($deletedFiles)) {
                $data =  FileHelpers::deleteFiles($deletedFiles);
            }
            /* delete file code ended */
            /* file validation and  uploaded code start*/
            if (!empty($availableFiles)) {
                $fileErrors = FileHelpers::prepareForValidation($availableFiles);
                $data = FileHelpers::prepareForValidation($availableFiles);
                if (!$data['status']) {
                    $response = [
                        'status' => 422,
                        'message' => 'Validation Failed',
                        'result' => [],
                        'errorDetails' => (object) $fileErrors,
                    ];
                } else {
                    if (!empty($data['resizeFiles'])) {
                        ProcessImages::dispatch($data['resizeFiles']);
                    }

                    $response = [
                        'status' => 200,
                        'message' => 'Success',
                        'result' => [],
                        'errorDetails' => '',
                    ];
                }
            } else {
                $response = [
                    'status' => 422,
                    'message' => 'No Files Found',
                    'result' => [],
                    'errorDetails' => [],
                ];
            }
        } catch (\Exception $ex) {
            $response = [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $ex->getMessage(),
                'result' => [],
            ];
        }

        return response()->json($response);
    }
    /*
    public function submit_old(Request $request)
    {

        try {
            $availableFilesJson = isset($request['availableFiles']) ? $request['availableFiles'] : '{}';
            $availableFiles = json_decode($availableFilesJson, true);
            if (!empty($availableFiles)) {
                $fileErrors = [];
                foreach ($availableFiles as $key => $value) {
                    //$fileDestination = $request['profile_image_orgFileDestination'];
                    $fieldName = $value['fieldName'];
                    $isValidation = $value['checkValidation'];
                    $fileDetails = $value['filesData'];

                    $resizeOptions = $value['resizeOptions'];

                    if ($isValidation) {
                        $validationRulesRequest = $value['validationRules'];
                        $validationRules = (!empty($validationRulesRequest)) ? $validationRulesRequest : [];
                    } else {
                        $validationRules = [];
                    }

                    if (!empty($validationRules)) {
                        $allowedType = $validationRules['allowedType'];
                        $maxFileSize = $validationRules['maxFileSize'];
                        $maxFileSizeType = $validationRules['maxFileSizeType'];

                        if (!empty($fileDetails)) {
                            foreach ($fileDetails as $file) {
                                $errors = [];
                                $fileName = $file['orgFileName'];
                                $fileDestination = $file['fileDestination'];


                                $imageData = S3Helpers::getImages($fileDestination, $fileName);


                                if (!File::isDirectory('temp_images')) {
                                    File::makeDirectory('temp_images', 0777, true, true);
                                }

                                $localPath = Storage::disk('public');
                                $local = 'temp_images/' . $fileName;
                                $localPath->put($local, file_get_contents($imageData), 'public');



                                $localUrl = Storage::path('temp_images/' . $fileName);
                                $fileObject = $this->createFileObject($localUrl);



                                $validationParams['allowedType'] = $allowedType;
                                $validationParams['maxFileSize'] = $maxFileSizeType;
                                $validationParams['maxFileSizeType'] = $maxFileSize;
                                $error = FileHelpers::checkValidation($validationParams, $fileObject);
                                if ($error != '') {
                                    $errors['field'] = $fieldName;
                                    $errors['message'] = $error;
                                    array_push($fileErrors, $errors);
                                }

                                Storage::delete('temp_images/' . $fileName);

                            }
                            Storage::deleteDirectory('temp_images/tmp-files');
                        }
                    }

                    if (empty($fileErrors)) {
                        foreach ($fileDetails as $file) {
                            $fileName_2 = $file['orgFileName'];
                            $fileDestination_2 = $file['fileDestination'];

                            if (!empty($resizeOptions)) {
                                foreach ($resizeOptions as $option) {

                                    $imageData_2 = S3Helpers::getImages($fileDestination_2, $fileName_2);



                                    $img = Image::make($imageData_2);
                                    $params = [
                                        'orgName' => $fileName_2,
                                        'img' => $img,
                                        'folder' => 'resizeImages',
                                        'height' => $option['height'],
                                        'width' => $option['width'],
                                    ];

                                    $resizeImage = FileHelpers::resizeImage($params);

                                }
                            }
                        }
                        $response = [
                            'status' => 200,
                            'message' => 'Success',
                            'result' => [],
                            'errorDetails' => '',
                        ];
                    } else {
                        $response = [
                            'status' => 422,
                            'message' => 'Failed',
                            'result' => [],
                            'errorDetails' => (object) $fileErrors,
                        ];

                        // error response //
                    }
                }
            }
        } catch (\Exception $ex) {
            $response = [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $ex->getMessage(),
                'result' => [],
            ];
        }

        return response()->json($response);
    }
    */


    /*This function is used to CreateFileObject From Url */
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
    /* This function is Used to Upload file from Give request */
    public function upload(Request $request)
    {
        $returnAry = [];
        $fieldName = $request->get('fieldName');
        $uploadedOn = $request->get('uploadOn');


        if ($request->file($fieldName)) {
            /* Upload file to S3*/
            $name = $request->file($fieldName)->getClientOriginalName();
            $imageName = uniqid() . $name;

            if ($uploadedOn == 's3') {
                $path = $request->file($fieldName)->storeAs(
                    $request->get('orgFileDestinationFolder'),
                    $imageName,
                    's3'
                );
                /* Upload file to S3*/

                $s3Url = Storage::disk('s3')->url($path);
            } else {

                $path = $request->file($fieldName)->storeAs(
                    $request->get('orgFileDestinationFolder'),
                    $imageName
                );
                /*fetch Absolute path Of uploaded File */
                $s3Url = Storage::url($request->get('orgFileDestinationFolder') . '/' . $imageName);
            }

            $returnAry['status'] = 200;
            $returnAry['originalName'] = $name;
            $returnAry['newName'] = $imageName;
            $returnAry['url'] = $s3Url;
        } else {
            $returnAry['status'] = 400;
            $returnAry['mesaage'] = 'File not Found.';
        }

        return response()->json($returnAry);
    }
}
