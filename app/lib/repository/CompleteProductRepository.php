<?php



namespace Walltwisters\lib\repository;

use Walltwisters\lib\model\CompleteProduct;
use Walltwisters\lib\model\ProductItemExtended;
use Walltwisters\lib\model\ProductDescription;
use Walltwisters\lib\model\ProductItem;
use Walltwisters\lib\model\ImageBaseInfo;


class CompleteProductRepository extends BaseRepository {
    public function __construct() {
        parent::__construct();
        
    }
    private $sql = "SELECT p.id
        , pd.description
        , pd.name 
        , la.id as language_id
        , la.language as language
        , co.id as country_id
        , co.country as country
        , sz.id as size_id
        , sz.sizes
        , ma.id as material_id
        , ma.material
        , im.id as image_id
        , im.image_name
        , ic.id as image_category_id
        , ic.category as image_category
        , im.size as image_size
        , form.format as product_format_name
        , p.formats_id as product_format_id
        FROM products p
        INNER JOIN products_items pit ON pit.product_id = p.id
        LEFT JOIN product_descriptions pd ON pd.product_id = p.id
        INNER JOIN languages la ON la.id = pd.language_id
        INNER JOIN countries co ON co.id = pit.country_id
        LEFT JOIN sizes sz ON sz.id = pit.size_id
        LEFT JOIN materials ma ON ma.id = pit.material_id
        LEFT JOIN products_images pi ON pi.product_id = p.id
        LEFT JOIN images im ON im.id = pi.image_id
        LEFT JOIN images_categories ic ON ic.id = im.images_category_id
        LEFT JOIN formats as form ON form.id = p.formats_id
        ";
    
    public function getAllCompleteProducts(){
        $sql = $this->sql . 'ORDER BY p.id';
        $stmt = self::$conn->prepare($sql);
        $res = $stmt->execute();                                                                        
        if ($res) {
           return $this->createArrayOfObjs($stmt);
        }
    }
    
    private function createArrayOfObjs($stmt){
         $stmt->bind_result(
                    $productId,
                    $description, 
                    $name, 
                    $languageId,
                    $language,
                    $countryId,
                    $country,
                    $sizeId, 
                    $size, 
                    $materialId, 
                    $material, 
                    $imageId,
                    $imageName,
                    $imageCategoryId,
                    $imageCategory, 
                    $imageSize,
                    $productFormatName,
                    $productFormatId                    
                    );
            $okfetch = $stmt->fetch();
            if ($okfetch) {
                $products = [];
                while ($stmt->fetch()) {
                    if(!isset($products[$productId])){
                        $products[$productId] = new CompleteProduct(); 
                        $products[$productId]->id = $productId;
                        $products[$productId]->formatId = $productFormatId;
                        $products[$productId]->formatName = $productFormatName;
                        $imagesHandled = [];
                        $addedProductMaterialSize = [];
                        $addedLanguages = [];
                    }
                    
                    if (!isset($addedLanguages[$languageId])) {
                        $products[$productId]->addProductDescription(
                                ProductDescription::createExtended($productId, $languageId, $language, $description, $name));
                        $addedLanguages[$languageId] = true;
                    }
                    if (!isset($addedProductMaterialSize[$materialId]) ||
                             !isset($addedProductMaterialSize[$materialId][$sizeId])) {
                         $products[$productId]->addItem(ProductItemExtended::createExtended( $productId, $countryId, $country, $sizeId, $size, $materialId, $material));

                         $addedProductMaterialSize[$materialId][$sizeId] = true;
                     }
                
                    if (!array_key_exists($imageId, $imagesHandled)) {
                        if ($imageSize > 0) {
                            $products[$productId]->addImageBaseInfo(ImageBaseInfo::createBaseInfo($imageId, $imageName, $imageCategoryId, $imageCategory));
                        }
                        $imagesHandled[$imageId] = true;
                    }
                }
                
                return $products;
            }
        
        throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
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
