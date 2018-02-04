<?php
namespace Walltwisters\model; 

class Slider {
    private $desktopImageId;
    private $mobileImageId;
    private $productId;
    private $userId;
    
    public static function create($mobileImageId, $desktopImageId, $productId, $userId){
        $obj = new Slider();
        $obj->mobileImageId = $mobileImageId;
        $obj->desktopImageId = $desktopImageId;
        $obj->productId = $productId;
        $obj->userId = $userId;
        
        return $obj;
    }
    
    public function __get($name){
        return $this->$name;
    }
    
    public function __set($name, $value){
        $this->$name = $value;
    }
}

