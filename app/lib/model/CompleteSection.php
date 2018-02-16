<?php

namespace Walltwisters\model;

class CompleteSection extends Section {
   private $imageBaseInfos;
   private $productIds;
   
   public function __construct(){
       $this->imageBaseInfos = [];
       $this->productIds = [];
   }
   
   public function __get($name){
        return $this->$name;
    }
    
    public function __set($name, $value){
        $this->$name = $value;
    }
   
   public static function createExtended($id,
                                  $titel, 
                                  $salesLineHeader,
                                  $salesLineParagraph,
                                  $languageId
                                  ){
       $obj = new CompleteSection();
       $obj->id = $id;
       $obj->titel = $titel;
       $obj->salesLineHeader = $salesLineHeader;
       $obj->languageId = $languageId;
       $obj->salesLineParagraph = $salesLineParagraph;
       
       return $obj;
   }
   
   public function addProductId($id){
       $this->productIds[] = $id;
   }
   
   public function addImageBaseInfo($imageBaseInfo){
       $this->imageBaseInfos[] = $imageBaseInfo;
   }
}
