<?php

require_once 'data/BaseRepository.php';
require_once 'model/ProductMaterial.php';

class ProductMaterialRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("materials", "ProductMaterial");
    }
    
    public function getAllMaterials() {
        return $this->getAllObjects();
    }
}
