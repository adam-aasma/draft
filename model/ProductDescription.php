<?php

require_once 'Language.php';
require_once 'Product.php';

class ProductDescription {
    private $descriptionText;
    private $productId;
    private $languageId;
    private $languageName;
    private $countryId;
    private $countryName;
    private $productName;
    
    public function __get($name) {
        return $this->$name;
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public static function create($productId, $languageId, $description, $countryId, $name){
        $obj = new ProductDescription();
        $obj->languageId = $languageId;
        $obj->productId = $productId;
        $obj->countryId = $countryId;
        $obj->descriptionText = $description;
        $obj->name = $name;
        return $obj;
    }
    
    public static function createExtended($productId, $languageId, $languageName, $description, $countryId, $countryName, $name) {
        $obj = self::create($productId, $languageId, $description, $countryId, $name);
        $obj->countryName = $countryName;
        $obj->languageName = $languageName;
        return $obj;
    }
}
