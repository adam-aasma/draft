<?php
namespace Walltwisters\repository; 

use Walltwisters\model\ProductMaterial;

class ProductMaterialRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("materials", "Walltwisters\model\ProductMaterial");
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
    
    
    
}
