<?php


class PrivilegeRepository extends BaseRepository {
    
    protected function getColumnNamesForInsert() {
        throw new Exception("Not implemented");
    }
    
    protected function getColumnValuesForBind($aggregate) {
        throw new Exception("Not implemented");
    }
    
    public function __construct() {
        parent::__construct("privileges", "Privileges");
    }
    
    public function getAllPrivileges() {
        return $this->getAll();
    }
}
