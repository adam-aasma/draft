<?php
namespace Walltwisters\lib\repository; 

class ProductSectionRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("products_sections", "Walltwisters\lib\model\ProductSection");
    }
    
    protected function getColumnNamesForInsert() {
        return ['product_id', 'section_id', 'country_id', 'language_id'];
    }
    
    protected function getColumnValuesForBind($productSection) {
        $product_id = $productSection->productId;
        $section_id = $productSection->sectionId;
        $country_id = $productSection->countryId;
        $language_id = $productSection->languageId;
        return [['i', &$product_id], ['i', &$section_id], ['i', $country_id], ['i', $language_id]];
    }
    
    public function getProductsForSectionId($obj){
        $sql = ("SELECT product_id FROM products_sections WHERE section_id = ? AND language_id = ? AND country_id = ?");
        $stmt = self::$conn->prepare($sql);
        $sectionId = $obj->sectionId;
        $languageId = $obj->languageId;
        $countryId = $obj->countryId;     
        $stmt->bind_param("iii",$sectionId , $languageId , $countryId) ;                                                              
        $res = $stmt->execute();
        $productIds = [];
        if ($res) {
            $stmt->bind_result($productId);
            while ($stmt->fetch()) {
                $productIds[] = $productId;
            }
        }
        
        return $productIds;
        
    }
}
