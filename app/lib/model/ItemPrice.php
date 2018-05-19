<?php
namespace Walltwisters\lib\model;


class ItemPrice {
   private $itemId;
   private $paperPrice;
   private $techniquePrice;
   private $labourPrice;
   private $totalPrice;
   
   public static function create($paperPrice,
                                 $techniquePrice,
                                 $labourPrice,
                                 $totalPrice){
      $obj = new ItemPrice();
      $obj->paperPrice = $paperPrice;
      $obj->techniquePrice = $techniquePrice;
      $obj->labourPrice = $labourPrice;
      $obj->totalPrice = $totalPrice;
      
      return $obj;
      
   }
   
   public function __get($name){
       return $this->$name;
   }
   
   public function __set($name, $value){
       $this->$name = $value;
   }
}
