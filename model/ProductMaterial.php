<?php


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
}
