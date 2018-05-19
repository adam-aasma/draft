<?php
namespace Walltwisters\lib\repository; 

use Walltwisters\lib\model\ItemMaterial;

class ItemMaterialRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("materials", "Walltwisters\lib\model\ItemMaterial");
    }
    
    protected function getColumnNamesForInsert() {
        return ['material', 'description'];
    }
    
    protected function getColumnValuesForBind($material) {
        $paper = $material->material;
        $description = $material->description;
       
        return [['s', &$paper], ['s', &$description]];
    }
    
    public function getAllMaterials() {
        return $this->getAll();
    }
    
    protected function createObjArray($stmt) {
        $stmt->bind_result($id, $material, $name);
        $objCollection = [];
        while($stmt->fetch()){
          $objCollection[]  = ItemMaterial::create($material, $name, $id);
        }
        return $objCollection;
    }
    
    
    
}
