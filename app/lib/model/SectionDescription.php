<?php
namespace Walltwisters\model;

class SectionDescription implements \JsonSerializable {
    protected $title;
    protected $saleslineHeader;
    protected $saleslineParagraph;
    protected $description;
    protected $sectionId;
    protected $languageId;
    
    
    public function __get($name) {
        return $this->$name;
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public static function create( $title, $saleslineHeader, $saleslineParagraph, $description, $languageId, $sectionId) {
        $obj = new sectionDescription();
        $obj->title = $title;
        $obj->saleslineHeader = $saleslineHeader;
        $obj->saleslineParagraph = $saleslineParagraph;
        $obj->description = $description;
        $obj->languageId = $languageId;
        $obj->sectionId = $sectionId;
        return $obj;
    }
    
    public function getIdArray(){
        return ["language_id" => $this->languageId, "section_id" => $this->sectionId];
        
    }
    
    public function jsonSerialize() {
        return ['title' => $this->title,
                'saleslineheader' => $this->saleslineHeader, 
                'saleslineParagraph' => $this->saleslineParagraph, 
                'description' => $this->description,
                'languageId' => $this->languageId,
                'sectionId' => $this->sectionId];
    }
}
