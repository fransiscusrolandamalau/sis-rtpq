<?php

namespace App\Traits;

use Closure;
use Image;
use Intervention\Image\Constraint;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileHelper
{
    /**
     * Save the uploaded image.
     *
     * @param UploadedFile $file     Uploaded file.
     * @param int          $maxWidth
     * @param string       $path
     * @param Closure      $callback Custom file naming method.
     *
     * @return string File name.
     */
    public static function saveImage(UploadedFile $file, $maxWidth = 150, $path = null, Closure $callback = null)
    {
        if (! $path) {
            $path = config('filesystems.uploads.images');
        }

        if ($callback) {
            $fileName = $callback();
        } else {
            $fileName = self::getFileName($file);
        }

        $img = self::makeImage($file);
        $img = self::resizeImage($img, $maxWidth);
        self::uploadImage($img, $fileName, $path);

        return $fileName;
    }

    /**
     * Get uploaded file's name.
     *
     *
     * @return null|string
     */
    protected static function getFileName(UploadedFile $file)
    {
        $filename = $file->getClientOriginalName();
        $filename = date('Ymd_His').'_'.strtolower(pathinfo($filename, PATHINFO_FILENAME)).'.'.pathinfo($filename, PATHINFO_EXTENSION);

        return $filename;
    }

    /**
     * Create the image from upload file.
     *
     *
     * @return \Intervention\Image\Image
     */
    protected static function makeImage(UploadedFile $file)
    {
        return Image::make($file);
    }

    /**
     * Resize image to the configured size.
     *
     * @param int                       $maxWidth
     *
     * @return \Intervention\Image\Image
     */
    protected static function resizeImage(\Intervention\Image\Image $img, $maxWidth = 150)
    {
        $img->resize($maxWidth, null, function (Constraint $constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        return $img;
    }

    /**
     * Save the uploaded image to the file system.
     *
     * @param \Intervention\Image\Image $img
     * @param string                    $fileName
     * @param string                    $path
     */
    protected static function uploadImage($img, $fileName, $path)
    {
        $img->save(public_path($path.$fileName));
    }
}
