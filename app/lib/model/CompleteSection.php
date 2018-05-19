<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Walltwisters\lib\model;


class CompleteSection implements \JsonSerializable{
    private $id;
    private $copies; //copies is an array of sectionDescription objects
    private $imageBaseInfos;
    private $productSections; //productSections
    
    public function __construct(){
        $this->copies = [];
        $this->imageBaseInfos = [];
        $this->productSections = [];
    }
    
     public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public function pushArrays($value, $property){
        $this->$property[] = $value;
    }
    
    public function pushArraysWithKey($property, $key, $value){
        $this->$property[$key] = $value;
    }
    
    public function jsonSerialize() {
        return ['sectionId' => $this->id, 'copies' => $this->copies, 'imageBaseinfos' => $this->imageBaseInfos, 'products' => $this->productSections ];
    }
    
    public function addProduct($productId, $languageId, $countryId){
        if(empty($this->findProduct($productId, $languageId, $countryId))){
            array_push($this->productSections, ProductSection::create($productId, $this->id, $countryId, $languageId));
        }
    }
    
    private function findProduct($productId, $languageId, $countryId){
        foreach($this->productSections as $productSection){
            if($productSection->productId === $productId &&
                $productSection->languageId === $languageId &&
                $productSection->countryId === $countryId) {
                return $productSection;
            }
        }
        return null;
    }
    
    
    
   
}
