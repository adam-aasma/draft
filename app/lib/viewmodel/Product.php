<?php
namespace Walltwisters\lib\viewmodel;

class Product {
   private $images;  //array of object image
   private $productinfo; 
   
   public function __get($name){
      return $this->$name;
   }
   
   public function __set($name, $value){
       $this->$name = $value;
   }
   
   public function create($images, $productinfo){
       $this->images = $images;
       $this->productinfo = $productinfo;
       return $this;
   }
}
