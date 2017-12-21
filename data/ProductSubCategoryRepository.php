<?php
require_once 'data/BaseRepository.php';
require_once 'model/ProductSubCategory.php';

class ProductSubCategoryRepository extends BaseRepository {
    
    public function __construct() {
        parent::__construct("products_subcategories", "ProductSubCategory");
    }
    
    public function getAllProductSubCategories() {
        return $this->getAllObjects();
    }
}