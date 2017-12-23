<?php

require_once 'data/BaseRepository.php';
require_once 'model/ProductMaterial.php';

class ProductMaterialRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("materials", "ProductMaterial");
    }
    
    function getColumnNamesForInsert() {
        throw new Exception("Not implemented");
    }
    
    function getColumnValuesForBind($aggregate) {
        throw new Exception("Not implemented");
    }
    
    public function getAllMaterials() {
        return $this->getAllObjects();
    }
}
