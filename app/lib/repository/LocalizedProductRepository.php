<?php
namespace Walltwisters\repository;

use Walltwisters\model\CompleteProduct;
use Walltwisters\viewmodel\ShowRoomProduct;
use Walltwisters\model\ItemExtended;
use Walltwisters\model\ProductDescription;
use Walltwisters\model\ProductItem;
use Walltwisters\model\ImageBaseInfo;


class LocalizedProductRepository extends BaseRepository {
    public function __construct() {
        parent::__construct();
    }
    
    private $sql = "SELECT p.id as product_id
        , pd.description
        , pd.name 
        , sz.id as size_id
        , sz.sizes
        , ma.id as material_id
        , ma.material
        , co.country as country
        , co.id as country_id
        , im.id as image_id
        , im.size as image_size
        , im.image_name as image_name
        , imc.id as image_category_id
        , imc.category as image_category
        FROM products p
        LEFT JOIN product_descriptions pd ON pd.product_id = p.id
        LEFT JOIN products_items pri ON pri.product_id = p.id
        LEFT JOIN sizes sz ON sz.id = pri.size_id
        LEFT JOIN countries co ON co.id = pri.country_id
        LEFT JOIN materials ma ON ma.id = pri.material_id
        LEFT JOIN products_images pi ON pi.product_id = p.id
        LEFT JOIN images im ON im.id = pi.image_id
        LEFT JOIN images_categories imc ON imc.id =im.images_category_id
        ";
   
    
    public function getLocalizedProductsByIds($ids, $languageObj){
        $languageId = $languageObj->id;
        $sql = $this->sql;
        $qs = array_map(function() { return '?'; }, $ids);
        $inclause = implode(',', $qs);
        $idsAndLanguageId = $ids;
        $idsAndLanguageId[] = 1;
        $bs = array_map(function() { return 'i'; }, $idsAndLanguageId );
        $stmt = self::$conn->prepare($sql . " WHERE pd.language_id = ? AND  p.id IN ($inclause) ORDER BY p.id, im.id" );         
        $bindTypes = implode('', $bs);
        $bindp = [];
        $bindp[] = &$bindTypes;
        $bindp[] = &$languageId;
        for ($i = 0; $i < count($ids); $i++) {
            $bindp[] = &$ids[$i];
        }
        call_user_func_array(array($stmt, "bind_param"), $bindp);
        $res = $stmt->execute();                                                                        
        if ($res) {
            return $this->createArrayOfLocalizedProducts($stmt, $languageId);
        }
        
        throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
    
    public function getLocalizedProductsByCountryAndLanguage($countryObj, $languageObj) {
        $sql = $this->sql . "WHERE pd.language_id = ? AND co.id = ? ORDER BY p.id, im.id";
        $stmt = self::$conn->prepare($sql);     
        $languageId = $languageObj->id;
        $countryId = $countryObj->id;
        $stmt->bind_param("ii", $languageId, $countryId);  
        $res = $stmt->execute();                                                                        
        if ($res) {
            return $this->createArrayOfLocalizedProducts($stmt, $languageId);
        } 
        throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
    
    
    private function createArrayOfLocalizedProducts($stmt, $languageId){
            $stmt->bind_result($productId, $description, $name, $sizeId, $size, $materialId, $material, $countryId, $country, $imageId, $imageSize, $imageName, $imageCategoryId, $imageCategory);
            $okfetch = $stmt->fetch();
            $currentLocalizedProduct = null;
            $localizedProducts = [];
            $addedProductMaterialSize = [];
            while ($okfetch) {  
                if (empty($currentLocalizedProduct) || $productId != $currentLocalizedProduct->id) {
                    $currentLocalizedProduct = new \Walltwisters\model\LocalizedProduct();
                    $currentLocalizedProduct->id = $productId;
                    $currentLocalizedProduct->productDescription = ProductDescription::create($productId, $languageId, $description, $name);
                    $localizedProducts[] = $currentLocalizedProduct;
                    $imagesHandled = [];
                    $itemsHandled = [];
                }
                if (!isset($addedProductMaterialSize[$productId]) || 
                        !isset($addedProductMaterialSize[$productId][$materialId]) ||
                        !isset($addedProductMaterialSize[$productId][$materialId][$sizeId])) {
                    $currentLocalizedProduct->addItem(ItemExtended::createExtended( $productId, $countryId, $country,  $sizeId, $size, $materialId, $material));
                 
                    $addedProductMaterialSize[$productId][$materialId][$sizeId] = true;
                }
                
                if (!array_key_exists($imageId, $imagesHandled)) {
                    if ($imageSize > 0) {
                        $currentLocalizedProduct->addImageBaseInfo(ImageBaseInfo::createBaseInfo($imageId, $imageName, $imageCategoryId, $imageCategory));
                    }
                    $imagesHandled[$imageId] = true;
                }
                $okfetch = $stmt->fetch();
            }
            
            return $localizedProducts;
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


