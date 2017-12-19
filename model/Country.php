<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Country
 *
 * @author adam
 */
class Country {
    private $id;
    private $name;
    
    public static function create($id, $name){
        $country = new Country();
        $country->id = $id;
        $country->name = $name;
        return $country;
    }
    public function __set($name, $value){
        $this->$name = $value;
    }
    
    public function __get($name){
        return $this->$name;
    }
}
