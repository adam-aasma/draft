<?php


class Size {
    private $id;
    private $sizes;
    private $name;
    
    
    
    public function __set($name, $value){
        $this->$name = $value;
    }
    public function __get($name){
        return $this->$name;
    }

}
