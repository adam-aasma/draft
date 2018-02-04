<?php
namespace Walltwisters\data; 

require_once 'data/BaseRepository.php';
require_once 'model/ProductFormat.php';


class ProductFormatRepository extends BaseRepository {
    
    public function __construct() {
        parent::__construct("formats", "Walltwisters\model\ProductFormat");
    }
    
    protected function getColumnNamesForInsert() {
        throw new Exception("Not implemented");
    }
    
    protected function getColumnValuesForBind($aggregate) {
        throw new Exception("Not implemented");
    }
    
    public function getAllFormats() {
        return $this->getAll();
    }
    //put your code here
}
