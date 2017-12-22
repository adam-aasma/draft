<?php


class Product {
    private $id;
    private $descriptions;
    private $userid;
    private $formatid;
    
    function __construct($id, 
        $format, $userid) {
        
        $this->id = $id;
        $this->descriptions = [];
        $this->userid = $userid;
        $this->formatid = $format;
    }
    
    public function getId() {
        return $this->id;
    }
    public function __get($name){
        return $this->$name;
    }
    
   
    public function getProductDescriptions(){
        return $this->descriptions;
    }
    
    public function addProductDescription($productDescription) {
        $this->descriptions[] = $productDescription;
    }
}
