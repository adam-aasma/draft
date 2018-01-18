<?php
namespace Walltwisters\viewmodel;

class Localization {
   private $country;
   private $language;
   
    public function __get($name){
      return $this->$name;
   }
   
   public function __set($name, $value){
       $this->$name = $value;
   }
}
