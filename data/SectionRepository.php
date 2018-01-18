<?php
namespace Walltwisters\data; 

require_once 'data/BaseRepository.php';
require_once 'model/Section.php';
class SectionRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("sections", "Walltwisters\model\Section");
    }
    
    protected function getColumnNamesForInsert() {
       return ['titel', 'sales_line', 'desktop_big_pic_id', 'desktop_small_pic_id', 'mobile_pic_id', 'language_id', 'created_by_user_id'];
       
    }
    
    protected function getColumnValuesForBind($section) {
        $titel = $section->titel;
        $sales_line = $section->salesLine;
        $big_pic_id = $section->desktopBigPicId;
        $small_pic_id = $section->desktopSmallPicId;
        $mobile_pic_id = $section->mobilePicId ;
        $language_id = $section->languageId;
        $added_by_user_id = $section->createdByUserId;
        
        return [['s', &$titel], ['s', &$sales_line], ['i', &$big_pic_id], ['i', &$small_pic_id], ['i', &$mobile_pic_id], ['i', &$language_id], ['i', &$added_by_user_id]];
    }
}
