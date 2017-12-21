<?php

require_once 'data/BaseRepository.php';
require_once 'model/ProductPrintTechnique.php';

class ProductPrintTechniqueRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("print_techniques", "ProductPrintTechnique");
    }
    
    public function getAllProductPrintTechniques() {
        return $this->getAllObjects();
    }

}
