<?php

require_once 'data/BaseRepository.php';
require_once 'model/ProductDescription.php';

class ProductDescriptionRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("product_descriptions", "ProductDescription");
    }
    
    function getColumnNamesForInsert() {
        throw new Exception("Not implemented");
    }
    
    function getColumnValuesForBind($aggregate) {
        throw new Exception("Not implemented");
    }
    
    public function getAllDescriptions() {
        return $this->getAllObjects();
    }

}
