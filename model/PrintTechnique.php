<?php


class PrintTechnique {
    private $id;
    private $technique;
    private $description;
    
    
    
    public function __set($name, $value){
        $this->$name = $value;
    }
    public function __get($name){
        return $this->$name;
    }


}
