<?php


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
    
    public function addItemDetails($size, $material, $technique) {
        if (!(empty($this->itemDetails))) {
            $this->itemDetails .= '; ';
        }
        $this->itemDetails .= sprintf("%s, %s, %s", $size, $material, $technique);
    }
}
