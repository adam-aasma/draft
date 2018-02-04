<?php
namespace Walltwisters\model; 

class ProductSize {
    private $id;
    private $sizes;
    private $name;
    
    public function __set($name, $value){
        $this->$name = $value;
    }
    public function __get($name){
        return $this->$name;
    }
    
    public static function create($sizes, $name='', $id=null){
        $obj = new ProductSize();
        $obj->sizes = $sizes;
        $obj->name = $name;
        $obj->id = $id;
        return $obj;
    }

}
