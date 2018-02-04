<?php
namespace Walltwisters\model; 


class Section {
    private $id;
    private $desktopBigPicId;
    private $desktopSmallPicId;
    private $mobilePicId;
    private $createdByUserId;
    
    
    public function __get($name){
        return $this->$name;
    }
    
    public function __set($name, $value){
        $this->$name = $value;
    }
    
    public static function create($desktopBigPicId, $desktopSmallPicId, $mobilePicId, $createdByUserId){
        $obj = new Section();
        $obj->desktopBigPicId = $desktopBigPicId;
        $obj->desktopSmallPicId = $desktopSmallPicId;
        $obj->mobilePicId = $mobilePicId;
        $obj->createdByUserId= $createdByUserId;
        
        return $obj;
    }
}



