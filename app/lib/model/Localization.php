<?php
namespace Walltwisters\model;

class Localization {
   private $country;
   private $language;
   
    public function __get($name){
      return $this->$name;
   }
   
   public function __set($name, $value){
       $this->$name = $value;
   }
   
   public function __construct(){
       $this->country = new Country();
       $this->language = new Language();
   }
           
   
   public static function create($country, $language){
       $obj = new Localization();
       $obj->country = $country;
       $obj->language = $language;
       
       return $obj;
   }
   
}
