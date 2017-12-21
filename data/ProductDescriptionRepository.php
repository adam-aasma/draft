<?php

require_once 'data/BaseRepository.php';
require_once 'model/ProductDescription.php';

class ProductDescriptionRepository {
    public function __construct() {
        parent::__construct("product_descriptions", "ProductDescription");
    }
    
    public function getAllDescriptions() {
        return $this->getAllObjects();
    }

}
