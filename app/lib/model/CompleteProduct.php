<?php
namespace Walltwisters\model; 

class CompleteProduct extends Product implements \JsonSerializable {
    protected $productDescriptions;
    protected $imageBaseInfos;
    protected $items;
    
    public function __construct() {
        $this->productDescriptions = [];
        $this->items = [];
        $this->imageBaseInfos = [];
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
    
    public function addProductDescription($productDescription) {
        $languageId = $productDescription->languageId;
        $this->productDescriptions[$languageId] = $productDescription;
    }
    
    public function jsonSerialize() {
        $images = [];
        $productDescriptions = [];
        $items = [];
        foreach($this->imageBaseInfos as $imageBaseInfo) {
            $images[] = $imageBaseInfo->jsonSerialize();
        }
        foreach($this->productDescriptions as $productDescription) {
            $productDescriptions[] = $productDescription->jsonSerialize();
        }
        foreach($this->items as $item) {
            $items[] = $item->jsonSerialize();
        }
        return [
            'images' => $images,
            'productDescriptions' => $productDescriptions,
            'items' => $items
        ];
    }
}
