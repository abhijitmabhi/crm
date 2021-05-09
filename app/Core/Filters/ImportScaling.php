<?php

namespace LocalheroPortal\Core\Filters;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class ImportScaling implements FilterInterface
{
    /**
     * Applies filter effects to given image
     *
     * @param  Image   $image
     * @return Image
     */
    public function applyFilter(Image $image): Image
    {
        if ($image->height() < $image->width()) {
            $image->resize(
                2560, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
        } else {
            $image->resize(
                null, 2000, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
        }
        $image->sharpen(2);
        return $image;
    }
}
