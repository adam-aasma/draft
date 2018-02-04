<?php
namespace Walltwisters\model; 

class ProductPrintTechnique {
    private $id;
    private $technique;
    private $description;
    
    public function __set($name, $value){
        $this->$name = $value;
    }
    public function __get($name){
        return $this->$name;
    }

    public static function create($technique, $description=''){
        $obj = new ProductPrintTechnique();
        $obj->technique = $technique;
        $obj->description = $description;
        return $obj;
    }
}
