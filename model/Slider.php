<?php
namespace Walltwisters\model; 

class Slider {
    private $imageid;
    private $productid;
    private $salesmessage;
    private $titel;
    private $userid;
    
    public function __construct($imageid, $productid,
            $salesmessage, $titel,
            $userid){
        $this->imageid = $imageid;
        $this->productid = $productid;
        $this->salesmessage = $salesmessage;
        $this->titel = $titel;
        $this->userid = $userid;
    }
    
    public function __get($name){
        return $this->$name;
    }
}

