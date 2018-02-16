<?php
namespace Walltwisters\repository;

use \Walltwisters\model\SectionDescription;

class SectionDescriptionRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("section_descriptions", "Walltwisters\model\SectionDescription");
    }
    
    protected function getColumnNamesForInsert() {
       return ['title', 'sales_line_header', 'sales_line_paragraph', 'country_id', 'language_id', 'section_id'];
       
    }
    
    protected function getColumnValuesForBind($section) {
        $titel = $section->title;
        $sales_line_header = $section->saleslineHeader;
        $sales_line_paragraph = $section->saleslineParagraph;
        $country_id = $section->countryId;
        $language_id = $section->languageId;
        $section_id = $section->id;
        
        return [
                ['s', &$titel],
                ['s', &$sales_line_header],
                ['s', &$sales_line_paragraph],
                ['i', &$country_id],
                ['i', &$language_id], 
                ['i', &$section_id]
               ];
    }

}
