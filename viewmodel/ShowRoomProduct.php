<?php


class ShowRoomProduct {
    private $name;
    private $descriptions;
    private $imageids;
    
    public function __construct() {
        $this->descriptions = [];
        $this->imageids = [];
    }
    
    public function __get($name){
        return $this->$name;
    }
    
    public function __set($name, $value){
        $this->$name = $value;
    }
    
    public function addDescription($text) {
        $this->descriptions[] = $text;
    }
    
    public function addImageId($val) {
        $this->imageids[] = $val;
    }
}
