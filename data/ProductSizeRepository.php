<?php

require_once 'data/BaseRepository.php';
require_once 'data/ProductSizeRepository.php';
require_once 'model/ProductSize.php';

class ProductSizeRepository extends Baserepository {
    public function __construct() {
        parent::__construct("sizes", "ProductSize");
    }
    
    protected function getColumnNamesForInsert() {
        throw new Exception("Not implemented");
    }
    
    protected function getColumnValuesForBind($aggregate) {
        throw new Exception("Not implemented");
    }
    
    public function getAllSizes() {
        return $this->getAll();
    }
}
