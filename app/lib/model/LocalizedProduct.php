<?php
namespace Walltwisters\lib\model; 

class LocalizedProduct extends Product {
    protected $productDescription;
    protected $imageBaseInfos;
    protected $items;
    
    public function __construct() {
        $this->imageBaseInfos = [];
        $this->items = [];
    }
    
    public function __get($name){
        return $this->$name;
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public function addImageBaseInfo($imageBaseInfo) {
        $this->imageBaseInfos[] = $imageBaseInfo;
    }
    
    public function addItem($item) {
        $this->items[] = $item;
    }
}
