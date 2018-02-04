<?php
namespace Walltwisters\repository; 

class PrivilegeRepository extends BaseRepository {
    
    protected function getColumnNamesForInsert() {
        throw new Exception("Not implemented");
    }
    
    protected function getColumnValuesForBind($aggregate) {
        throw new Exception("Not implemented");
    }
    
    public function __construct() {
        parent::__construct("privileges", "Walltwisters\model\Privileges");
    }
    
    public function getAllPrivileges() {
        return $this->getAll();
    }
}
