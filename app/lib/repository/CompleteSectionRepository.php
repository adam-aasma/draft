<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Walltwisters\repository;

use Walltwisters\model\CompleteSection;
use Walltwisters\model\ImageBaseInfo;
use Walltwisters\model\SectionDescription;

class CompleteSectionRepository extends BaseRepository {
    private $sql = "SELECT s.id,
                    s.desktop_big_pic_id,
                    i1.images_category_id as bigpic_category_id,
                    i1.image_name as bigpic_name,
                    ic1.category as bigpic_category,
                    s.desktop_small_pic_id,
                    i2.images_category_id as smallpic_category_id,
                    i2.image_name as smallpic_name,
                    ic2.category as smallpic_category,
                    s.mobile_pic_id,
                    i3.images_category_id as mobilepic_category_id,
                    i3.image_name as mobilepic_name,
                    ic3.category as mobilepic_category,
                    sd.language_id,
                    l.language,
                    IF(ps.country_id IS NULL, -1, ps.country_id) as country_id,
                    IF (c.country IS NULL, '', c.country) as country,
                    sd.title,
                    sd.sales_line_header,
                    sd.sales_line_paragraph,
                    sd.section_description,
                    COUNT(ps.product_id) as product_count
                    FROM sections s
                    LEFT JOIN section_descriptions sd ON sd.section_id = s.id
                    LEFT JOIN languages l ON l.id = sd.language_id
                    LEFT JOIN products_sections ps ON ps.section_id = s.id AND ps.language_id = sd.language_id
                    LEFT JOIN countries c ON c.id = ps.country_id
                    LEFT JOIN images i1 ON i1.id = s.desktop_big_pic_id
                    LEFT JOIN images i2 ON i2.id = s.desktop_small_pic_id
                    LEFT JOIN images i3 ON i3.id = s.mobile_pic_id
                    LEFT JOIN images_categories ic1 ON ic1.id = i1.images_category_id
                    LEFT JOIN images_categories ic2 ON ic2.id = i2.images_category_id
                    LEFT JOIN images_categories ic3 ON ic3.id = i3.images_category_id
                    GROUP BY s.id,
                    s.desktop_big_pic_id,
                    i1.images_category_id,
                    i1.image_name,
                    ic1.category,
                    s.desktop_small_pic_id,
                    i2.images_category_id,
                    i2.image_name,
                    ic2.category,
                    s.mobile_pic_id,
                    i3.images_category_id,
                    i3.image_name,
                    ic3.category,
                    sd.language_id,
                    l.language,
                    ps.country_id,
                    c.country,
                    sd.title,
                    sd.sales_line_header,
                    sd.sales_line_paragraph,
                    sd.section_description
";


    
    public function getAllCompleteSections(){
        $sql = $this->sql;
        $stmt = self::$conn->prepare($sql);
        $res = $stmt->execute();                                                                        
        if ($res) {
           $sectionRows =$this->fetchRows($stmt);
        }
        return $this->buildObjs($sectionRows);
    }
    
    private function buildObjs($sectionArrays){
        $sections = [];
        foreach($sectionArrays as $sectionId => $sectionArray){
            $section = new CompleteSection();
            $section->id = $sectionId;
            $section->pushArrays(ImageBaseInfo::createBaseInfo($sectionArray['bigPicId'], $sectionArray['bigPicName'], $sectionArray['bigPicCategoryId'], $sectionArray['bigPicCategory']), 'imageBaseInfos');
            $section->pushArrays(ImageBaseInfo::createBaseInfo($sectionArray['smallPicId'], $sectionArray['smallPicName'], $sectionArray['smallPicCategoryId'], $sectionArray['smallPicCategory']),'imageBaseInfos');
            $section->pushArrays(ImageBaseInfo::createBaseInfo($sectionArray['mobilePicId'], $sectionArray['mobilePicName'], $sectionArray['mobilePicCategoryId'], $sectionArray['mobilePicCategory']), 'imageBaseInfos');
            foreach($sectionArray as $value) {
                if(!is_array($value)){
                    continue;
                }
                $section->pushArrays(SectionDescription::create($value['title']
                                                                        , $value['salesLineHeader']
                                                                        , $value['salesLineParagraph']
                                                                        , $value['sectionDescription']
                                                                        , $value['languageId']
                                                                        , $sectionId), 'copies') ;
                foreach($value as $products){
                    if(!is_array($products)){
                        continue;
                    }
                    $section->pushArrays($products, 'products');
                }

            }
            array_push($sections, $section);
            
        }
        
        return $sections;
        
    }
            
    
    private function fetchRows($stmt){
        $stmt->bind_result(
                    $sectionId,
                    $bigPicId, 
                    $bigPicCategoryId, 
                    $bigPicName, 
                    $bigPicCategory, 
                    $smallPicId, 
                    $smallPicCategoryId, 
                    $smallPicName, 
                    $smallPicCategory, 
                    $mobilePicId,
                    $mobilePicCategoryId,
                    $mobilePicName,
                    $mobilePicCategory,
                    $languageId, 
                    $language,
                    $countryId, 
                    $country,
                    $title,
                    $slineHeader,
                    $slineParagraph,
                    $description, 
                    $productCount
                    );
        $sections = [];
        while ($stmt->fetch()) {
            $sections[$sectionId]['sectionId'] = $sectionId;
            $sections[$sectionId]['bigPicId'] = $bigPicId;
            $sections[$sectionId]['bigPicCategoryId'] = $bigPicCategoryId;
            $sections[$sectionId]['bigPicName'] = $bigPicName;
            $sections[$sectionId]['bigPicCategory'] = $bigPicCategory;
            $sections[$sectionId]['smallPicId'] = $smallPicId;
            $sections[$sectionId]['smallPicCategoryId'] = $smallPicCategoryId;
            $sections[$sectionId]['smallPicName'] = $smallPicName;
            $sections[$sectionId]['smallPicCategory'] = $smallPicCategory;
            $sections[$sectionId]['mobilePicId'] = $mobilePicId;
            $sections[$sectionId]['mobilePicCategoryId'] = $mobilePicCategoryId;
            $sections[$sectionId]['mobilePicName'] = $mobilePicName;
            $sections[$sectionId]['mobilePicCategory'] = $mobilePicCategory;
            $sections[$sectionId][$languageId]['languageId'] = $languageId;
            $sections[$sectionId][$languageId]['language'] = $language;
            $sections[$sectionId][$languageId]['title'] = $title;
            $sections[$sectionId][$languageId]['salesLineHeader'] = $slineHeader;
            $sections[$sectionId][$languageId]['salesLineParagraph'] = $slineParagraph;
            $sections[$sectionId][$languageId]['sectionDescription'] = $description;
            $sections[$sectionId][$languageId][$countryId] = [
                'countryId' => $countryId,
                'country' => $country,
                'productCount' => $productCount
            ];
        }

        return $sections;
    }
    
    
    
    
    
    
    
    
    
    /*
     * not implemented abstract functions asked for from baseRepository
     */
     protected function getColumnNamesForInsert() {
       /*not implemented */;
    }

    protected function getColumnValuesForBind($notimplemented) {
        /*not implemented */
}
}