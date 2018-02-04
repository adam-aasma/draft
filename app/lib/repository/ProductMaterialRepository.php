<?php
namespace Walltwisters\repository; 

require_once 'data/BaseRepository.php';
require_once 'model/ProductMaterial.php';

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
