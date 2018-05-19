<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Walltwisters\lib\model;


class SectionDescriptionExtended extends SectionDescription  implements \JsonSerializable  {
    protected $language;
    
    public static function createExtended( $title, $saleslineHeader, $saleslineParagraph, $description, $languageId, $language) {
        $obj = new sectionDescriptionExtended();
        $obj->title = $title;
        $obj->saleslineHeader = $saleslineHeader;
        $obj->saleslineParagraph = $saleslineParagraph;
        $obj->description = $description;
        $obj->languageId = $languageId;
        $obj->language = $language;
        return $obj;
    }
    
    public function jsonSerialize() {
        return ['title' => $this->title,
                'saleslineheader' => $this->saleslineHeader, 
                'saleslineParagraph' => $this->saleslineParagraph, 
                'description' => $this->description,
                'languageId' => $this->languageId,
                'language' => $this->language];
    }
}
