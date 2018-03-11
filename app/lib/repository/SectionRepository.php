<?php
namespace Walltwisters\repository; 

use Walltwisters\model\CompleteSection;
use Walltwisters\model\ImageBaseInfo;

class SectionRepository extends BaseRepository {
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
    
    public function getCompleteSectionBy($country, $language){
        $countryId = $country->id;
        $languageId = $language->id;
        $sql = ("SELECT s.id,
                sd.title, 
                sd.sales_line_header,
                sd.sales_line_paragraph,
                i.id as image_id,
                i.image_name,
                i.images_category_id,
                ic.category,
                sd.language_id,
                ps.product_id as product_id
                FROM sections s
                LEFT JOIN section_descriptions sd ON sd.section_id = s.id
                LEFT JOIN images i ON i.id IN(s.desktop_big_pic_id, s.desktop_small_pic_id, s.mobile_pic_id)
                LEFT JOIN images_categories ic ON ic.id = i.images_category_id
                LEFT JOIN products_sections ps ON ps.section_id = s.id
                WHERE sd.country_id  = ? AND sd.language_id = ?
                ORDER BY s.id, i.id
                ");
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("ii", $countryId, $languageId);
        $res = $stmt->execute();
        $completeSection = null;
        $completeSections = [];
        $inx = 0;
        if ($res) {
            $stmt->bind_result($sectionId, $sectionTitel, $salesLineHeader, $salesLineParagraph, $imageId, $imageName, $imageCategoryId, $imageCategory, $language_id, $productId);
            while ($stmt->fetch()) {
                if(empty($completeSection) || $completeSection->id != $sectionId){
                    if($inx != 0){
                        $completeSections [] = $completeSection;
                    }
                    $completeSection = CompleteSection::createExtended($sectionId, $sectionTitel, $salesLineHeader, $salesLineParagraph, $language_id);
                    $imagesHandled = [];
                    $productIdsHandled = [];
                    $inx += 1;
                   
                }
                if(!array_key_exists($imageId, $imagesHandled)){
                    $completeSection->addImageBaseInfo(\Walltwisters\model\ImageBaseInfo::createBaseInfo($imageId, $imageName, $imageCategoryId, $imageCategory));
                    $imagesHandled[$imageId] = true;
                }
                if(!array_key_exists($productId, $productIdsHandled)){
                    $completeSection->addProductId($productId);
                    $productIdsHandled[$productId] = $productId;
                }
               
            }
            $completeSections[] = $completeSection;
        }
        
        return $completeSections;
    }
    
   public function getAllSectionsByCountryLanguage($country, $language){
        $countryId= $country->id;
        $languageId = $language->id;
        $sql = ("SELECT  
                 sd.title,
                 sd.sales_line_header,
                 sd.sales_line_paragraph,
                 sd.country_id,
                 c.country,
                 sd.language_id,
                 l.language,
                 sd.section_id
                 FROM section_descriptions sd
                 INNER JOIN countries c ON c.id = sd.country_id
                 INNER JOIN languages l ON l.id = sd.language_id
                 WHERE country_id = ? AND language_id = ?" );
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("ii", $countryId, $languageId); 
        $res = $stmt->execute();
        if ($res){
            $stmt->bind_result($title, $saleslineHeader, $saleslineParagraph, $countryid, $country, $languageid, $language, $sectionId);
            $sections = [];
            while ($stmt->fetch()){
                $localization = \Walltwisters\model\Localization::create(\Walltwisters\model\Country::create($countryid, $country), \Walltwisters\model\Language::create($languageid, $language));
                $sections[] = \Walltwisters\model\SectionBaseInfo::createBaseInfo($title, $saleslineHeader, $saleslineParagraph, $localization, $sectionId);
            }
            
            return $sections;
        }
    } 
    
    public function getCompleteSectionsById($id){
        $sql = ("SELECT
                 s.id as section_id,
                 sd.id as section_description_id,
                 s.desktop_big_pic_id,
                 s.desktop_small_pic_id,
                 s.mobile_pic_id,
                 s.created_by_user_id,
                 s.creation_date,
                 sd.title,
                 sd.sales_line_header,
                 sd.sales_line_paragraph,
                 sd.country_id,
                 c.country,
                 sd.language_id,
                 l.language,
                 i.id as image_id,
                 i.image_name,
                 i.images_category_id,
                 ic.category,
                 ps.product_id
                 FROM sections s
                 INNER JOIN section_descriptions sd ON sd.section_id = s.id
                 INNER JOIN countries c ON c.id = sd.country_id
                 INNER JOIN languages l ON l.id = sd.language_id
                 LEFT JOIN images i ON i.id IN(s.desktop_big_pic_id, s.desktop_small_pic_id, s.mobile_pic_id)
                 LEFT JOIN images_categories ic ON ic.id = i.images_category_id
                 LEFT JOIN products_sections ps ON ps.section_id = s.id
                 WHERE s.id = ?
                 ORDER BY sd.id, i.images_category_id");
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $res = $stmt->execute();
        if ($res){
            $stmt->bind_result( $sectionId,
                                $sectionDescriptionId,
                                $desktopBigId,
                                $desktopSmallId,
                                $mobileId, 
                                $addedById, 
                                $creationDate,
                                $title, 
                                $saleslineHeader,
                                $saleslineParagraph,    
                                $countryId,
                                $country,
                                $languageId,
                                $language,
                                $imageId,
                                $imageName,
                                $imageCategoryId,
                                $imageCategory,
                                $productId
                                );
            $completeSection = null;
            while ($stmt->fetch()){
                if(empty($completeSection) || $completeSection->id != $sectionId){
                    $completeSection = new \Walltwisters\model\CompleteSection2();
                    $completeSection->id = $sectionId;
                    $imagesHandled = [];
                    $productIdsHandled = [];
                    $sectionDescriptionHandled = [];
                }
                
                if(!array_key_exists($imageId, $imagesHandled)){
                    $completeSection->addToArray(\Walltwisters\model\ImageBaseInfo::createBaseInfo($imageId, $imageName, $imageCategoryId, $imageCategory), 'imageBaseInfos');
                    $imagesHandled[$imageId] = true;
                }
                
                if(!array_key_exists($sectionDescriptionId, $sectionDescriptionHandled)){
                    $localization = \Walltwisters\model\Localization::create(\Walltwisters\model\Country::create($countryId, $country), \Walltwisters\model\Language::create($languageId, $language));
                    $sectionBaseInfo = \Walltwisters\model\SectionBaseInfo::createBaseInfo($title, $saleslineHeader, $saleslineParagraph, $localization, $sectionId);
                    $completeSection->addToArray($sectionBaseInfo, 'sectionBaseInfos');
                    $imagesHandled[$imageId] = true;
                    $sectionDescriptionHandled[$sectionDescriptionId] = true;
                    
                }
                if(!array_key_exists($productId, $productIdsHandled)){
                    $completeSection->addToArray($productId, 'productIds');
                    $productIdsHandled[$productId] = $productId;
                }
               
            }
            
            
                   
        }
        
        return $completeSection;

                 
    }
}
