<?php

class Language {
    private $id;
    private $language;
    
    public function __get($name) {
         return $this->$name;
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    
}
