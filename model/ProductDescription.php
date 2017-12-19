<?php

require_once 'Language.php';
require_once 'Product.php';

class ProductDescription {
    private $id;
    private $descriptionText;
    private $product;
    private $language;
    
    public function getId() {
        return $this->id;
    }
    
    public function getDescriptionText() {
        return $this->descriptionText;
    }
    
    public function getLanguage() {
        return $this->language;
    }
    
    public function getProduct() {
        return $this->product;
    }
    
    public function setId($id) {
        $this->id = $id;
    }
    
    public function setDescriptionText($text) {
        $this->descriptionText = $text;
    }
    
    public function setLanguage($language) {
        $this->language = $language;
    }
    
    public function setProduct($product) {
        $this->product = $product;
    }
}
