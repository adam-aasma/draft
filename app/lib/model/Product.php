<?php
namespace Walltwisters\lib\model; 

class Product {
    protected $id;
    protected $artistdesignerid;
    protected $createdByUserId;
    protected $updatedByUserId;
    protected $formatid;
    protected $formatName;
    protected $sectionId;
    protected $sectionName;
    
    public static function create(
        $id, 
        $artistDesignerId,
        $createdByUserId,
        $updatedByUserId,    
        $formatId) {
        $obj = new Product();
        $obj->id = $id;
        $obj->artistdesignerid = $artistDesignerId;
        $obj->createdByUserId = $createdByUserId;
        $obj->updatedByUserId = $updatedByUserId;
        $obj->formatid = $formatId;
        return $obj;
    }
    
    public function __get($name){
        return $this->$name;
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
}
