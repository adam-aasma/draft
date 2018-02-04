<?php
namespace Walltwisters\model; 

class ProductMaterial {
    private $id;
    private $material;
    private $description;
    
    public function __set($name, $value){
        $this->$name = $value;
    }
    
    public function __get($name){
        return $this->$name;
    }
    
    public static function create($material, $description=''){
        $obj = new ProductMaterial();
        $obj->material = $material;
        $obj->description = $description;
        return $obj;
    }
}
