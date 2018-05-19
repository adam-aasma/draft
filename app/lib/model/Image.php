<?php
namespace Walltwisters\lib\model; 

class Image extends ImageBaseInfo {
    protected $stream;
    protected $size;
    protected $mime;
    
    public static function create($stream, $size, $mime, $name, $categoryId = null) {
        $obj = new Image();
        $obj->stream = $stream;
        $obj->size = $size;
        $obj->mime = $mime;
        $obj->imageName = $name;
        $obj->categoryId = $categoryId;
        return $obj;
    }
    
    public function __get($name) {
        return $this->$name;
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
}


