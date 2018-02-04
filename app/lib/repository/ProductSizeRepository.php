<?php
namespace Walltwisters\data; 

require_once 'data/BaseRepository.php';
require_once 'data/ProductSizeRepository.php';
require_once 'model/ProductSize.php';

class ProductSizeRepository extends Baserepository {
    public function __construct() {
        parent::__construct("sizes", "Walltwisters\model\ProductSize");
    }
    
    protected function getColumnNamesForInsert() {
        return ['sizes', 'name'];
    }
    
    protected function getColumnValuesForBind($size) {
        $sizes = $size->sizes;
        $name = $size->name;
        

        return [['s', &$sizes], ['s', &$name]];
    }
    
    public function getAllSizes() {
        return $this->getAll();
    }
}
