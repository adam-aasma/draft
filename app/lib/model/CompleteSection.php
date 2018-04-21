<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Walltwisters\model;


class CompleteSection implements \JsonSerializable{
    private $id;
    private $copies; //copies is an array of sectionDescription objects
    private $imageBaseInfos;
    private $products;
    
    public function __construct(){
        $this->copies = [];
        $this->imageBaseInfos = [];
        $this->products = [];
    }
    
     public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public function pushArrays($value, $property){
        $this->$property[] = $value;
    }
    
    public function jsonSerialize() {
        return ['sectionId' => $this->id, 'copies' => $this->copies, 'imageBaseinfos' => $this->imageBaseInfos, 'products' => $this->products ];
    }
    
    
    
   
}
