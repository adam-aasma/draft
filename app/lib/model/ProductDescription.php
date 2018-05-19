<?php
namespace Walltwisters\lib\model; 

class ProductDescription implements \JsonSerializable {
    private $descriptionText;
    private $productId;
    private $languageId;
    private $languageName;
    private $name;
    
    public function __get($name) {
        return $this->$name;
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public function getIdArray() {
        return [ 'product_id' => $this->productId, 'language_id' => $this->languageId];
    }
    
    public static function create($productId, $languageId, $description, $name){
        $obj = new ProductDescription();
        $obj->languageId = $languageId;
        $obj->productId = $productId;
        $obj->descriptionText = $description;
        $obj->name = $name;
        return $obj;
    }
    
    public static function createExtended($productId, $languageId, $languageName, $description, $name) {
        $obj = self::create($productId, $languageId, $description, $name);
        $obj->languageName = $languageName;
        return $obj;
    }
    
    public function jsonSerialize() {
        return ['languageId' => $this->languageId, 'name' => $this->name, 'description' => $this->descriptionText];
    }
}
