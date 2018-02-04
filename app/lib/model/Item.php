<?php
namespace Walltwisters\model; 

class Item {
    private $id;
    private $productId;
    private $sizeId;
    private $sizes;
    private $materialId;
    private $material;
    private $printTechniqueId;
    private $printTechnique;
    private $printerId;
    
    public function __get($name) {
        return $this->$name;
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public static function create($sizeId, $materialId, $printTechniqueId = null, $printerId = null, $id = null) {
        $item = new Item();
        $item->sizeId = $sizeId;
        $item->materialId = $materialId;
        $item->printTechniqueId = $printTechniqueId;
        $item->printerId = $printerId;
        $item->id = $id;
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
            $printTechnique,
            $printerId = null) {
        $item = new Item();
        $item->productId = $productId;
        $item->id = $itemId;
        $item->sizeId = $sizeId;
        $item->sizes = $sizes;
        $item->materialId = $materialId;
        $item->material = $material;
        $item->printTechniqueId = $printTechniqueId;
        $item->printTechnique = $printTechnique;
        $item->printerId = $printerId;
        return $item;
    }
}
