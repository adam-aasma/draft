<?php
namespace Walltwisters\model; 

class Language {
    private $id;
    private $language;
    
    public function __get($name) {
         return $this->$name;
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public static function create($id, $name){
        $obj = new Language;
        $obj->id = $id;
        $obj->language = $name;
        return $obj;
    }
    
}
