<?php


namespace Walltwisters\lib\model;


class item {
    private $id;
    private $sizeId;
    private $materialId;
    private $printTechniqueId;
    private $printerId;
    
    public function __set($name, $value){
        $this->$name = $value;
    }
    public function __get($name){
        return $this->$name;
    }
    
    public static function create($sizeId, $materialId, $printTechniqueId, $printerId, $itemId = null){
        $obj = new item();
        $obj->sizeId = $sizeId;
        $obj->materialId = $materialId;
        $obj->printTechniqueId = $printTechniqueId;
        $obj->printerId = $printerId;
        $obj->id = $itemId;
        return $obj;
        
    }
    
 
    
}
