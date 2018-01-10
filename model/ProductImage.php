<?php
namespace Walltwisters\model; 



class ProductImage {
    private $productId;
    private $imageId;

    public function __get($name) {
        return $this->$name;
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }

    public static function create($productId, $imageId){
        $obj = new ProductImage();
        $obj->productId = $productId;
        $obj->imageId = $imageId;
        return $obj;
    }
}
