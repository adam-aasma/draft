<?php
namespace Walltwisters\model; 

class Country {
    private $id;
    private $country;
    
    public static function create($id, $country){
        $obj = new Country();
        $obj->id = $id;
        $obj->country = $country;
        return $obj;
    }
    public function __set($name, $value){
        $this->$name = $value;
    }
    
    public function __get($name){
        return $this->$name;
    }
}


