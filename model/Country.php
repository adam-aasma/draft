<?php


class Country {
    private $id;
    //private $name;
    private $country;
    
    public static function create($id, $country){
        $country = new Country();
        $country->id = $id;
        $country->country = $country;
        return $country;
    }
    public function __set($name, $value){
        $this->$name = $value;
    }
    
    public function __get($name){
        return $this->$name;
    }
}
