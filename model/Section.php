<?php
namespace Walltwisters\model; 

class Section {
    private $id;
    private $titel;
    private $salesLine;
    private $desktopBigPicId;
    private $desktopSmallPicId;
    private $mobilePicId;
    private $languageId;
    private $createdByUserId;
    
    
    public function __get($name){
        return $this->$name;
    }
    
    public function __set($name, $value){
        $this->$name = $value;
    }
    
    public static function create($name, $salesLine, $desktopBigPicId, $desktopSmallPicId, $mobilePicId, $languageId, $createdByUserId){
        $obj = new Section();
        $obj->titel = $name;
        $obj->salesLine = $salesLine;
        $obj->desktopBigPicId = $desktopBigPicId;
        $obj->desktopSmallPicId = $desktopSmallPicId;
        $obj->mobilePicId = $mobilePicId;
        $obj->languageId = $languageId;
        $obj->createdByUserId= $createdByUserId;
        
        return $obj;
    }
}



