<?php
namespace Walltwisters\repository; 

class ProductPrintTechniqueRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("print_techniques", "Walltwisters\model\ProductPrintTechnique");
    }
    
    protected function getColumnNamesForInsert() {
        return ['technique', 'description'];
    }
    
    protected function getColumnValuesForBind($technique) {
        $tech = $technique->technique;
        $description = $technique->description;
       
        return [['s', &$tech], ['s', &$description]];
    }
    
    public function getAllProductPrintTechniques() {
        return $this->getAll();
    }

}
