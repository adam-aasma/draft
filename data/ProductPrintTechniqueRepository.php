<?php

require_once 'data/BaseRepository.php';
require_once 'model/ProductPrintTechnique.php';

class ProductPrintTechniqueRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("print_techniques", "ProductPrintTechnique");
    }
    
    function getColumnNamesForInsert() {
        throw new Exception("Not implemented");
    }
    
    function getColumnValuesForBind($aggregate) {
        throw new Exception("Not implemented");
    }
    
    public function getAllProductPrintTechniques() {
        return $this->getAllObjects();
    }

}
