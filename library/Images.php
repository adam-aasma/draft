<?php
use Walltwisters\model\Image;

class Images {
    public static function getImageData($imagefiles){
        $productimages = [];
        foreach ($imagefiles as $imagefile){
            $filepath = $imagefile["tmp_name"];
            $mime = $imagefile["type"];
            $size = $imagefile["size"];
            $image = Image::create($filepath, $size, $mime);
            $images[] = $image;
        }
        return $images;
        }
    }
    

