<?php

require_once 'data/BaseRepository.php';
require_once 'data/ProductSizeRepository.php';
require_once 'model/ProductSize.php';

class ProductSizeRepository extends Baserepository {
    public function __construct() {
        parent::__construct("sizes", "ProductSize");
    }
    
    public function getAllSizes() {
        return $this->getAllObjects();
    }
}
