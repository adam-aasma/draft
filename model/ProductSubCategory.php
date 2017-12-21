<?php
require_once 'data/BaseRepository.php';
require_once 'model/ProductSubCategory.php';

class ProductSubCategory {
    private $id;
    private $subcategory;
    private $description;
    
   
    
    public function __get($name){
        return $this->$name;
    }
}
