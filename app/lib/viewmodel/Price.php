<?php
namespace Walltwisters\lib\viewmodel;

class Price {
    private $paper;
    private $printing;
    private $size;
    private $currency;
    
     public function __get($name){
      return $this->$name;
   }
   
   public function __set($name, $value){
       $this->$name = $value;
   }
}
