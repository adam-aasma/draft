<?php


class Slider {
    private $imageid;
    private $productid;
    private $languageid;
    private $countryid;
    private $salesmessage;
    private $titel;
    private $userid;
    
    public function __construct($imageid, $productid,
            $languageid, $countryid, $salesmessage, $titel,
            $userid){
        $this->imageid = $imageid;
        $this->productid = $productid;
        $this->languageid = $languageid;
        $this->countryid = $countryid;
        $this->salesmessage = $salesmessage;
        $this->titel = $titel;
        $this->userid = $userid;
    }
    
    public function __get($name){
        return $this->$name;
    }
}

