<?php
namespace Walltwisters\lib\repository; 

use Walltwisters\lib\model\ItemPrintTechnique;

class ItemPrintTechniqueRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("print_techniques", "Walltwisters\lib\model\ItemPrintTechnique");
    }
    
    protected function getColumnNamesForInsert() {
        return ['technique', 'description'];
    }
    
    protected function getColumnValuesForBind($technique) {
        $tech = $technique->technique;
        $description = $technique->description;
       
        return [['s', &$tech], ['s', &$description]];
    }
    
    public function getAllItemPrintTechniques() {
        return $this->getAll();
    }
    
    protected function createObjArray($stmt) {
        $stmt->bind_result($id, $technique, $description);
        $objCollection = [];
        while($stmt->fetch()){
          $objCollection[]  = ItemPrintTechnique::create($technique, $description, $id);
        }
        return $objCollection;
    }

}
