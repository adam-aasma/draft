<?php
namespace Walltwisters\model; 

require_once 'ImageBaseInfo.php';

class Image extends ImageBaseInfo {
    protected $filepath;
    protected $size;
    protected $mime;
    protected $categoryId;
    
    public static function create($filepath, $size, $mime, $categoryId=null) {
        $obj = new Image();
        $obj->filepath = $filepath;
        $obj->size = $size;
        $obj->mime = $mime;
        $obj->categoryId = $categoryId;
        return $obj;
    }
    
    public function __get($name) {
        return $this->$name;
    }
}


