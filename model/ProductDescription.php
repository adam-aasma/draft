<?php

require_once 'Language.php';
require_once 'Product.php';

class ProductDescription {
    private $id;
    private $descriptionText;
    private $product;
    private $language;
    private $country;
    
    public function __get($name) {
        return $this->$name;
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
}
