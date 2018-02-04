<?php
namespace Walltwisters\model; 



class Item2 {
   private $paper;
   private $technique;
   private $size;
   
   public static function create($paper, 
                                 $technique,
                                 $size){
      $obj = new Item2();
      $obj->paper = $paper;
      $obj->technique = $technique;
      $obj->size = $size;
      
      return $obj;
      
   }
   
   public function __get($name){
       return $this->$name;
   }
   
   public function __set($name, $value){
       $this->$name = $value;
   }
}
