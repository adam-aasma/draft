<?php


class PrivilegeRepository extends BaseRepository {
    
    public function __construct() {
        parent::__construct("privileges", "Privileges");
    }
    
    public function getAllPrivileges() {
        return $this->getAllObjects();
    }
}
