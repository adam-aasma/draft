<?php

namespace Walltwisters\data;

require_once 'data/BaseRepository.php';
require_once 'model/Slider.php';
class SliderRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("sliders", "Walltwisters\model\Slider");
    }
   
    protected function getColumnNamesForInsert() {
        return ['desktop_image_id', 'product_id', 'added_by_user', "mobile_image_id"];
    }
    
    protected function getColumnValuesForBind($slider) {
        $image_id = $slider->desktopImageId;
        $product_id = $slider->productId;
        $added_by_user_id = $slider->userId;
        $mobile_image_id = $slider->mobileImageId;        

        return [['i', &$image_id], ['i', &$product_id], ['i', &$added_by_user_id], ['i', &$mobile_image_id]];
    }
}
