<?php
namespace Walltwisters\lib\viewmodel;

class ProductListRow {
    private $productId;
    private $name;
    private $description;
    private $images;
    private $itemDetails;
    
    public function __construct() {
        $this->images = [];
        $this->itemDetails = '';
    }
    
    public function __get($name){
        return $this->$name;
    }
    
    public function __set($name, $value){
        $this->$name = $value;
    }
    
    public function addImage($imageId, $imageName) {
        $this->images[] = ['id' => $imageId, 'name' => $imageName];
    }
    
    public function addItemDetails($size, $material) {
        $this->itemDetails .= sprintf("<span>%s, %s</span>", $size, $material);
    }
}
