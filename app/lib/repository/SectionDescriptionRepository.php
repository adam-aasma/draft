<?php
namespace Walltwisters\lib\repository;

use Walltwisters\lib\model\SectionDescription;

class SectionDescriptionRepository extends BaseRepository {
    
    public function __construct() {
        parent::__construct("section_descriptions", "Walltwisters\lib\model\SectionDescription");
    }
    
    protected function getColumnNamesForInsert() {
       return ['title', 'sales_line_header', 'sales_line_paragraph', 'language_id', 'section_id', 'section_description'];
       
    }
    
    protected function getColumnValuesForBind($section) {
        $titel = $section->title;
        $sales_line_header = $section->saleslineHeader;
        $sales_line_paragraph = $section->saleslineParagraph;
        $description = $section->description;
        $language_id = $section->languageId;
        $section_id = $section->sectionId;
        
        return [
                ['s', &$titel],
                ['s', &$sales_line_header],
                ['s', &$sales_line_paragraph],
                ['i', &$language_id], 
                ['i', &$section_id],
                ['s', &$description]
               ];
    }
    
    

}
