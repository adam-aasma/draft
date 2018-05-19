<?php
namespace Walltwisters\lib\model; 

class ItemPrintTechnique {
    private $id;
    private $technique;
    private $description;
    
    public function __set($name, $value){
        $this->$name = $value;
    }
    public function __get($name){
        return $this->$name;
    }

    public static function create($technique, $description='', $id = null){
        $obj = new ItemPrintTechnique();
        $obj->id = $id;
        $obj->technique = $technique;
        $obj->description = $description;
        return $obj;
    }
    
    public function getIdArray(){
        return ['technique' => $this->technique];
    }

}
