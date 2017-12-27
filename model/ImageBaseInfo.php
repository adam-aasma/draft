<?php

class ImageBaseInfo {
    protected $id;
    protected $name;
    
    public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public static function createBaseInfo($id, $name) {
        $obj = new ImageBaseInfo();
        $obj->id = $id;
        $obj->name = $name;
        return $obj;
    }
}
