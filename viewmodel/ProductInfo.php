<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of productInfo
 *
 * @author ToivoAasma
 */
class productInfo {
   private $localization;
   private $type;         //portrait or landscape
   private $price;
   
    public function __get($name){
      return $this->$name;
   }
   
   public function __set($name, $value){
       $this->$name = $value;
   }
}
