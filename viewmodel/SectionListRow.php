<?php
namespace Walltwisters\viewmodel;

class SectionListRow {
    private $id;
    private $titel;
    private $salesLineHeader;
    private $salesLineParagraph;
    private $languageId;
    private $desktopImages;
    private $mobileImage;
    private $productIds;
    
    public function __construct(){
       $this->desktopImages = '';
       $this->productIds = [];
    }
    
    public function __get($name){
        return $this->$name;
    }
    
    public function __set($name, $value){
        $this->$name = $value;
    }
    
    public static function create($id, $titel, $salesLineHeader, $salesLineParagraph, $languageId){
        $obj = new sectionListRow();
        $obj->id = $id;
        $obj->titel = $titel;
        $obj->salesLineHeader = $salesLineHeader;
        $obj->salesLineParagraph = $salesLineParagraph;
        $obj->languageId = $languageId;
        
        return $obj;
    }
    
    public function addDesktopImageId($id){
        $html = '<img alt="picture" src=getimage.php?id=' . $id . ' style="width: 10px;"</img>' ;
        $this->desktopImages .= $html;
    }
    
    public function addMobileImageId($id){
        $html = '<img alt="picture" src=getimage.php?id=' . $id . ' style="width: 10px;"</img>' ;
        $this->mobileImage = $html;
    }
    
    public function addproductId($id){
        $this->productIds[]= $id;
    }
}
