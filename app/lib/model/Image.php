<?php
namespace Walltwisters\model; 

class Image extends ImageBaseInfo {
    protected $filepath;
    protected $size;
    protected $mime;
    
    public static function create($filepath, $size, $mime, $name, $categoryId = null) {
        $obj = new Image();
        $obj->filepath = $filepath;
        $obj->size = $size;
        $obj->mime = $mime;
        $obj->imageName = $name;
        $obj->categoryId = $categoryId;
        return $obj;
    }
    
    public function __get($name) {
        return $this->$name;
    }
}


