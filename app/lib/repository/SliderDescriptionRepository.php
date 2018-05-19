<?php
namespace Walltwisters\lib\repository;

class SliderDescriptionRepository extends BaseRepository {
   public function __construct() {
        parent::__construct("sliders", "Walltwisters\lib\model\Slider");
    }
   
    protected function getColumnNamesForInsert() {
        return ['language_id', 'country_id', 'title', 'salesline', 'slider_id'];
    }
    
    protected function getColumnValuesForBind($sliderDescription) {
        $language_id = $sliderDescription->languageId;
        $country_id = $sliderDescription->countryId;
        $title = $sliderDescription->title;
        $salesline = $sliderDescription->salesline;
        $slider_id = $sliderDescription->sliderId;
        

        return [['i', &$language_id], ['i', &$country_id], ['s', &$title], ['s', &$salesline], ['i', &$slider_id]];
    }
}
