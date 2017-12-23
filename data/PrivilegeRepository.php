<?php


class PrivilegeRepository extends BaseRepository {
    
    function getColumnNamesForInsert() {
        throw new Exception("Not implemented");
    }
    
    function getColumnValuesForBind($aggregate) {
        throw new Exception("Not implemented");
    }
    
    public function __construct() {
        parent::__construct("privileges", "Privileges");
    }
    
    public function getAllPrivileges() {
        return $this->getAllObjects();
    }
}
