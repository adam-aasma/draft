<?php
namespace Walltwisters\lib\repository; 

class ProductFormatRepository extends BaseRepository {
    
    public function __construct() {
        parent::__construct("formats", "Walltwisters\lib\model\ProductFormat");
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
