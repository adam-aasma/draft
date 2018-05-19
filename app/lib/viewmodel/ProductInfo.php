<?php
namespace Walltwisters\lib\viewmodel;


class productInfo {
   private $localization;
   private $type;         
   private $price;
   
    public function __get($name){
      return $this->$name;
   }
   
   public function __set($name, $value){
       $this->$name = $value;
   }
}
