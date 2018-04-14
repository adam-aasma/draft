<?php
namespace Walltwisters\model;

class SectionDescription {
    protected $title;
    protected $saleslineHeader;
    protected $saleslineParagraph;
    protected $description;
    protected $sectionId;
    private $languageId;
    
    
    public function __get($name) {
        return $this->$name;
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public static function create( $title, $saleslineHeader, $saleslineParagraph, $countryId, $languageId, $sectionId) {
        $obj = new sectionDescription();
        $obj->title = $title;
        $obj->saleslineHeader = $saleslineHeader;
        $obj->saleslineParagraph = $saleslineParagraph;
        $obj->description = $countryId;
        $obj->languageId = $languageId;
        $obj->sectionId = $sectionId;
        return $obj;
    }
    
    public function getIdArray(){
        return ["language_id" => $this->languageId, "section_id" => $this->sectionId];
        
    }
}
