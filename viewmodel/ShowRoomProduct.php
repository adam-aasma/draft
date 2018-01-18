<?php
namespace Walltwisters\viewmodel;

class ShowRoomProduct {
    private $productInfo;
    private $imageids;
    
    public function __construct() {
        $this->productInfo = [];
        $this->imageids = [];
    }
    
    public function __get($name){
        return $this->$name;
    }
    
    public function __set($name, $value){
        $this->$name = $value;
    }
    
    
    public function addInfo($obj) {
        $this->productInfo[] = $obj;
    }
    
    public function addImageId($val) {
        $this->imageids[] = $val;
    }
}
