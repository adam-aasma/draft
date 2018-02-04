<?php

namespace Walltwisters\model;


class SliderDescription {
    private $sliderId;
    private $languageId;
    private $countryId;
    private $title;
    private $salesline;
    
    
     public static function create($sliderid, $languageid, $countryid, $title, $salesline){
        $obj = new SliderDescription();
        $obj->sliderId = $imageid;
        $obj->languageId = $languageid;
        $obj->countryId = $countryid;
        $obj->title = $title;
        $obj->salesline = $salesline;
        
        return $obj;
    }
    
    public function __get($name){
        return $this->$name;
    }
    
    public function __set($name, $value){
        $this->$name = $value;
    }
}
