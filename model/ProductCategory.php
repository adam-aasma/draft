<?php


class ProductCategory {
    private $id;
    private $name;
    private $description;
    
    
    
    public function __get($name){
        return $this->$name;
    }
}



