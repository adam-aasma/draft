<?php



namespace Walltwisters\model;


class ProductItem {
    private $productId;
    private $itemId;
    
    public function __get($name){
       return $this->$name;
   }
   
   public function __set($name, $value){
       $this->$name = $value;
   }
   
   public static function create($productId, $itemId){
       $obj = new ProductItem();
       $obj->productId = $productId;
       $obj->itemId = $itemId;
       
       return $obj;
   }
}
