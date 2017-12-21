<?php

require_once 'data/BaseRepository.php';
require_once 'model/ProductCategory.php';
class ProductCategoryRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("products_categories", "ProductCategory");
    }
    
    public function getAllProductCategories() {
        return $this->getAllObjects();
    }
}
