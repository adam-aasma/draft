<?php


class ProductList {
    private $productId;
    private $localization;
    private $name;
    private $description;
    private $iamges;
    private $itemDetails;
    
    public function __get($name){
        return $this->$name;
    }
    
    public function __set($name, $value){
        $this->$name = $value;
    }
}
