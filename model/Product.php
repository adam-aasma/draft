<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of product
 *
 * @author adam
 */
class Product {
    private $id;
    private $name;
    private $descriptions;
    private $userid;
    
    function __construct($id, 
        $name, $userid) {
        
        $this->id = $id;
        $this->name = $name;
        $this->descriptions = [];
        $this->userid = $userid;
    }
    
    public function getId() {
        return $this->id;
    }
    public function __get($name){
        return $this->$name;
    }
    
    public function getProductName(){
        return $this->name;
    }
    
    public function getProductDescriptions(){
        return $this->descriptions;
    }
    
    public function addProductDescription($productDescription) {
        $this->descriptions[] = $productDescription;
    }
}
