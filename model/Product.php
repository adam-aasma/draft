<?php


class Product {
    protected $id;
    protected $artistdesignerid;
    protected $userid;
    protected $formatid;
    
    public static function create(
        $id, 
        $artistDesignerId,
        $userId, 
        $formatId) {
        $obj = new Product();
        $obj->id = $id;
        $obj->artistdesignerid = $artistDesignerId;
        $obj->userid = $userId;
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
