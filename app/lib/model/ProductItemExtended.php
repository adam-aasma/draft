<?php


namespace Walltwisters\lib\model; 

use Walltwisters\lib\model\ProductItem;



class ProductItemExtended extends ProductItem implements \JsonSerializable {
    protected $size;
    protected $material;
    protected $country;
   
    
    public function __get($name) {
        return $this->$name;
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public static function createExtended($productId, $countryId, $country, $sizeId, $size, $materialId, $material) {
        $obj= new ProductItemExtended();
        $obj->productId = $productId;
        $obj->countryId = $countryId;
        $obj->country = $country;
        $obj->sizeId = $sizeId;
        $obj->size = $size;
        $obj->materialId = $materialId;
        $obj->material = $material;
        return $obj;
    }

    public function jsonSerialize() {
        return ['countryId' => $this->countryId, 'sizeId' => $this->sizeId, 'materialId' => $this->materialId];
    }
}
