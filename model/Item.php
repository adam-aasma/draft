<?php

class Item {
    private $id;
    private $productId;
    private $sizeId;
    private $sizes;
    private $materialId;
    private $material;
    private $printTechniqueId;
    private $printTechnique;
    
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

    public static function createExtended(
            $itemId, 
            $productId, 
            $sizeId, 
            $sizes, 
            $materialId, 
            $material, 
            $printTechniqueId, 
            $printTechnique) {
        $item = new Item();
        $item->id = $itemId;
        $item->productId = $productId;
        $item->sizeId = $sizeId;
        $item->sizes = $sizes;
        $item->materialId = $materialId;
        $item->material = $material;
        $item->printTechniqueId = $printTechniqueId;
        $item->printTechnique = $printTechnique;
        return $item;
    }
}
