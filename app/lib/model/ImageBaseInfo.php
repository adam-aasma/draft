<?php
namespace Walltwisters\model; 

class ImageBaseInfo {
    protected $id;
    protected $categoryId;
    protected $category; //type of picture, product, interior, slider etc.
    protected $imageName;
    
    public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }
    // $category is what kind of pic... interior, product, slider etc...
    public static function createBaseInfo($id, $imageName, $categoryId, $category = null) {
        $obj = new ImageBaseInfo();
        $obj->id = $id;
        $obj->imageName = $imageName;
        $obj->categoryId = $categoryId;
        $obj->category = $category;
        return $obj;
    }
}
