<?php
namespace Walltwisters\model;


class ProductItem {
    protected $productId;
    protected $countryId;
    protected $materialId;
    protected $sizeId;
    
    public function __get($name){
       return $this->$name;
   }
   
   public function __set($name, $value){
       $this->$name = $value;
   }
   
   public static function create($productId, $countryId, $materialId, $sizeId){
       $obj = new ProductItem();
       $obj->productId = $productId;
       $obj->countryId = $countryId;
       $obj->materialId = $materialId;
       $obj->sizeId = $sizeId;
       
       return $obj;
   }
}
