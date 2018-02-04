<?php

namespace Walltwisters\model;


class CompleteSection2 {
    private $id;
    private $imageBaseInfos;
    private $sectionBaseInfos;
    private $productIds;
    
    public function __construct(){
        $this->imageBaseInfos = [];
        $this->sectionBaseInfos = [];
        $this->productIds = [];
    }
    
    public function __get($name){
        return $this->$name;
    }
    
    public function __set($name, $value){
        $this->$name = $value;
    }
    
    public function addToArray($value, $name){
        $this->$name[] = $value;
    }
    
    
}
