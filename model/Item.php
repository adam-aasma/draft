<?php

class Item {
    private $productId;
    private $sizeId;
    private $materialId;
    private $printTechniqueId;
    
    public function __get($name) {
        return $this->$name;
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public static function create($productId, $sizeId, $materialId, $printTechniqueId) {
        $item = new Item();
        $item->productId = $productId;
        $item->sizeId = $sizeId;
        $item->materialId = $materialId;
        $item->printTechniqueId = $printTechniqueId;
        return $item;
    }
}
