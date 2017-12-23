<?php

require_once 'data/BaseRepository.php';
require_once 'model/ProductCategory.php';
class ProductCategoryRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("products_categories", "ProductCategory");
    }
    
    function getColumnNamesForInsert() {
        throw new Exception("Not implemented");
    }
    
    function getColumnValuesForBind($aggregate) {
        throw new Exception("Not implemented");
    }
    
    public function getAllProductCategories() {
        return $this->getAllObjects();
    }
}
