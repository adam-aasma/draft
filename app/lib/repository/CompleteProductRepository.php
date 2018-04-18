<?php



namespace Walltwisters\repository;


class CompleteProductRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("sections", "Walltwisters\model\Section");
        
    }
    
    protected function getColumnNamesForInsert() {
       return ['desktop_big_pic_id', 'desktop_small_pic_id', 'mobile_pic_id','created_by_user_id'];
       
    }
    
    protected function getColumnValuesForBind($section) {
        $big_pic_id = $section->desktopBigPicId;
        $small_pic_id = $section->desktopSmallPicId;
        $mobile_pic_id = $section->mobilePicId ;
        $added_by_user_id = $section->createdByUserId;
        
        return [
                ['i', &$big_pic_id],
                ['i', &$small_pic_id],
                ['i', &$mobile_pic_id], 
                ['i', &$added_by_user_id],
               ];
    }
}
