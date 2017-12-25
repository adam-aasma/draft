<?php

class ProductSection {
    private $productId;
    private $sectionId;

    public function __get($name) {
        return $this->$name;
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }

    public static function create($productId, $sectionId){
        $obj = new ProductSection();
        $obj->productId = $productId;
        $obj->sectionId = $sectionId;
        return $obj;
    }
}
