<?php
namespace Walltwisters\lib\model; 

class ItemMaterial {
    private $id;
    private $material;
    private $description;
    
    public function __set($name, $value){
        $this->$name = $value;
    }
    
    public function __get($name){
        return $this->$name;
    }
    
    public static function create($material, $description='', $id= null){
        $obj = new ItemMaterial();
        $obj->id = $id;
        $obj->material = $material;
        $obj->description = $description;
        return $obj;
    }
    
     public function getIdArray(){
        return ['material' => $this->material];
    }
}
