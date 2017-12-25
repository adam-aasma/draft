<?php

require_once 'data/BaseRepository.php';
require_once 'model/ProductPrintTechnique.php';

class ProductPrintTechniqueRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("print_techniques", "ProductPrintTechnique");
    }
    
    protected function getColumnNamesForInsert() {
        throw new Exception("Not implemented");
    }
    
    protected function getColumnValuesForBind($aggregate) {
        throw new Exception("Not implemented");
    }
    
    public function getAllProductPrintTechniques() {
        return $this->getAll();
    }

}
