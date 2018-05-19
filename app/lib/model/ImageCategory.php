<?php
namespace Walltwisters\lib\model;


class ImageCategory {
    private $id;
    private $category;
    private $description;
    
    public function __get($name) {
        return $this->$name;
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public static function create($id, $category, $description = '') {
        $obj = new ImageCategory();
        $obj->id = $id;
        $obj->category = $category;
        $obj->description = $description;
        return $obj;
    }
}
