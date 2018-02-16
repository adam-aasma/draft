<?php
namespace Walltwisters\model;

class SectionBaseInfo {
    protected $title;
    protected $saleslineHeader;
    protected $salelineParagraph;
    protected $localization;
    protected $id;
    
    
    public function __get($name){
      return $this->$name;
   }
   
   public function __set($name, $value){
       $this->$name = $value;
   }
   
   public function __construct() {
       $this->localization = new Localization();
   }
   
   public static function createBaseInfo($title, $saleslineHeader, $saleslineParagraph, $localization, $sectionId){
       $obj = new SectionBaseInfo();
       $obj->title = $title;
       $obj->saleslineHeader = $saleslineHeader;
       $obj->salelineParagraph = $saleslineParagraph;
       $obj->localization = $localization;
       $obj->id = $sectionId;
       
       return $obj;
   }
}
