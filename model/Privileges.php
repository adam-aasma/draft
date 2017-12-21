<?php


class Privileges {
    private $id;
    private $privileges;
    private $description;
    
    function __construct(){
        
    }
    
    public function __get($name){
        return $this->$name;
    }
    
    public function __set($name, $value){
        $this->$name = $value;
    }
    
     

}
