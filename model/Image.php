<?php
require_once 'ImageBaseInfo.php';

class Image extends ImageBaseInfo {
    protected $filepath;
    protected $size;
    protected $mime;
    protected $category;
    
    public static function create($filepath, $size, $mime, $name, $category) {
        $obj = new Image();
        $obj->filepath = $filepath;
        $obj->size = $size;
        $obj->mime = $mime;
        $obj->name = $name;
        $obj->category = $category;
        return $obj;
    }
    
    public function __get($name) {
        return $this->$name;
    }
}


