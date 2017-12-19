<?php


class Images {
    public static function getImageData($imagefiles){
        $productimages = [];
        foreach ($imagefiles as $imagefile){
            $filepath = $imagefile["tmp_name"];
            $mime = $imagefile["type"];
            $size = $imagefile["size"];
            $file = [ 'filepath' => $filepath, 'mime' => $mime, 'size' => $size ];
            $productimages[] = $file;
        }
        
        if ($productimages){
            return $productimages;
        } else {
            return false;
        }
    }
    
}
