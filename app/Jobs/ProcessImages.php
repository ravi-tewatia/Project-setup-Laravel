<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helper\FileHelpers;
use App\Helper\S3Helpers;
use Image;

class ProcessImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $availableFiles;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($availableFiles)
    {
        $this->availableFiles = $availableFiles;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->availableFiles as $key => $value) {
            $filesData = isset($value['filesData']) ? $value['filesData'] : [];
            $resizeOptions = isset($value['resizeOptions']) ? $value['resizeOptions'] : [];
            $uploadedTo = $value['uploadedTo'];
            if (!empty($filesData)) {
                foreach ($filesData as $file) {
                    $fileName = $file['newName'];
                    $fileDestination = $file['fileDestination'];
                    if (!empty($resizeOptions)) {
                        foreach ($resizeOptions as $option) {
                            /* Get Image From S3 :: START */
                            if ($uploadedTo == 's3') {
                                $imageData = S3Helpers::getImages($fileDestination, $fileName);
                            } else {
                                /* Get Image from local */
                                $imageData = FileHelpers::getUploadedFileUrl($fileDestination, $fileName);
                            }
                            /* Get Image From S3 and local :: END */

                            /* For Resize :: START :: */
                            $img = Image::make($imageData);
                            $params = [
                                'orgName' => $fileName,
                                'img' => $img,
                                'folder' => $option['resizeDestinationFolder'],
                                'height' => $option['height'],
                                'width' => $option['width'],
                                's3Folder' => $option['resizeDestinationFolder'],
                                'uploadedTo' => $value['uploadedTo']
                            ];

                            $resizeImage = FileHelpers::resizeImage($params);
                            /* For Resize :: END :: */
                        }
                    }
                }
            }
        }
    }
}
