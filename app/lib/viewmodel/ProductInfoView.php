<?php
namespace Walltwisters\lib\viewmodel;


class ProductInfoView {
    private $country;
    private $language;
    private $productName;
    private $description;


    public function __get($name){
        return $this->$name;
    }
    
    public function __set($name, $value){
        $this->$name = $value;
    }
    
    public static function create($country, $language, $productName, $description){
        $obj = new ProductInfoView();
        $obj->country = $country;
        $obj->language = $language;
        $obj->productName = $productName;
        $obj->description = $description;
        
        return $obj;
        
    }
}