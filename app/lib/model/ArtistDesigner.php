<?php
namespace Walltwisters\lib\model;


class artistDesigner {
    private $id;
    private $artistDesigner;
    private $description;
    
    public static function create($id, $artistDesigner, $description = ''){
        $obj = new artistDesigner();
        $obj->id = $id;
        $obj->artistDesigner = $artistDesigner;
        $obj->description = $description;
        return $obj;
    }
    public function __set($name, $value){
        $this->$name = $value;
    }
    
    public function __get($name){
        return $this->$name;
    }
}
