<?php
namespace Walltwisters\lib\model; 

class ProductSection implements \JsonSerializable {
    private $productId;
    private $sectionId;
    private $countryId;
    private $languageId;

    public function __get($name) {
        return $this->$name;
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }

    public static function create($productId, $sectionId, $countryId, $languageId){
        $obj = new ProductSection();
        $obj->productId = $productId;
        $obj->sectionId = $sectionId;
        $obj->countryId = $countryId;
        $obj->languageId = $languageId;
        return $obj;
    }
    
    public function getIdArray(){
        return ["country_id" => $this->countryId, "section_id" => $this->sectionId, "language_id" => $this->languageId];
        
    }
    
    public function jsonSerialize(){
        return ['productId' => $this->productId,
                'sectionId' => $this->sectionId ,
                'countryId' => $this->countryId, 
                'languageId' => $this->languageId
                ];
    }
    
    
}
