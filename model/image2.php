<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of image2
 *
 * @author ToivoAasma
 */
class image2 {
    private $data;
    private $type;
    private $size;
    private $mime;
    private $filepath;
    
    public function __get($name){
      return $this->$name;
   }
   
   public function __set($name, $value){
       $this->$name = $value;
   }
   
  
}
