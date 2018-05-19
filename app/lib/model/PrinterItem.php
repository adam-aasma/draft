<?php
namespace Walltwisters\lib\model; 



class PrinterItem {
   private $paper;
   private $technique;
   private $size;
   
   public static function create($paper, 
                                 $technique,
                                 $size){
      $obj = new PrinterItem();
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
