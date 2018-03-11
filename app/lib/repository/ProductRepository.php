<?php
namespace Walltwisters\repository; 

use Walltwisters\model\CompleteProduct;
use Walltwisters\viewmodel\ShowRoomProduct;
use Walltwisters\model\ItemExtended;
use Walltwisters\model\ProductDescription;
use Walltwisters\model\ProductItem;
use Walltwisters\model\ImageBaseInfo;

class ProductRepository extends BaseRepository {
    
    public function __construct() {
        parent::__construct("products", "Walltwisters\model\Product");
    }
    
    protected function getColumnNamesForInsert() {
        return ['formats_id', 'artist_designer_id', 'added_by_user_id', 'updated_by_user_id'];
    }
    
    protected function getColumnNamesForUpdate() {
        return ['formats_id', 'artist_designer_id', 'updated_by_user_id'];
    }
    
    protected function getColumnValuesForBind($product) {
        $artist_designer_id = $product->artistdesignerid;
        $user_id1 = $product->createdByUserId;
        $user_id2 = $product->updatedByUserId;
        $format_id = $product->formatid;
        
        return [['i', &$format_id], ['i', &$artist_designer_id], ['i', &$user_id1], ['i', &$user_id2]];
    }
    
    protected function getColumnValuesForBindUpdate($product) {
        $artist_designer_id = $product->artistdesignerid;
        $user_id = $product->updatedByUserId;
        $format_id = $product->formatid;
        
        return [['i', &$format_id], ['i', &$artist_designer_id], ['i', &$user_id]];
    }
    
    public function getCompleteProductById($productId) {
        $sql = "SELECT pd.description
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
        ,form.format as product_format_name
        ,p.formats_id as product_format_id
        FROM products p
        LEFT JOIN product_descriptions pd ON pd.product_id = p.id
        INNER JOIN languages la ON la.id = pd.language_id
        INNER JOIN countries_languages cl on cl.language_id = la.id
        INNER JOIN countries co ON co.id = cl.country_id
        INNER JOIN products_items pit ON pit.product_id = p.id
        LEFT JOIN sizes sz ON sz.id = pit.size_id
        LEFT JOIN materials ma ON ma.id = pit.material_id
        LEFT JOIN products_images pi ON pi.product_id = p.id
        LEFT JOIN images im ON im.id = pi.image_id
        LEFT JOIN images_categories ic ON ic.id = im.images_category_id
        LEFT JOIN formats as form ON form.id = p.formats_id
        WHERE p.id = ?
        ORDER BY co.id, la.id
        ";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("i", $productId);                                                              
        $res = $stmt->execute();                                                                        
        if ($res) {
            $stmt->bind_result(
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
                $product = new CompleteProduct();
                $product->id = $productId;
                $product->formatId = $productFormatId;
                $product->formatName = $productFormatName;
                $imagesHandled = [];
                $addedProductMaterialSize = [];
                $addedLanguages = [];
                while ($okfetch) {
                    $okfetch = $stmt->fetch();
                    if (!isset($addedLanguages[$languageId])) {
                        $product->addProductDescription(
                                ProductDescription::createExtended($productId, $languageId, $language, $description, $name));
                        $addedLanguages[$languageId] = true;
                    }
                    if (!isset($addedProductMaterialSize[$materialId]) ||
                             !isset($addedProductMaterialSize[$materialId][$sizeId])) {
                         $product->addItem(ItemExtended::createExtended( $productId, $countryId, $country, $sizeId, $size, $materialId, $material));

                         $addedProductMaterialSize[$materialId][$sizeId] = true;
                     }
                
                    if (!array_key_exists($imageId, $imagesHandled)) {
                        if ($imageSize > 0) {
                            $product->addImageBaseInfo(ImageBaseInfo::createBaseInfo($imageId, $imageName, $imageCategoryId, $imageCategory));
                        }
                        $imagesHandled[$imageId] = true;
                    }
                }
                
                return $product;
            }
        }
        throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
    
    public function getLocalizedProductsByCountryAndLanguage($countryobj, $languageobj) {
        $sql = "SELECT p.id as product_id
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
        WHERE pd.language_id = ? AND co.id = ?
        ORDER BY p.id, im.id;
        ";
        $stmt = self::$conn->prepare($sql);     
        $language_Id = $languageobj->id;
        $country_Id = $countryobj->id;
        $stmt->bind_param("ii", $language_Id, $country_Id);                                                              
        $res = $stmt->execute();                                                                        
        if ($res) {
            $stmt->bind_result($productId, $description, $name, $sizeId, $size, $materialId, $material, $countryId, $country, $imageId, $imageSize, $imageName, $imageCategoryId, $imageCategory);
            $okfetch = $stmt->fetch();
            $currentLocalizedProduct = null;
            $localizedProducts = [];
            $addedProductMaterialSize = [];
            while ($okfetch) {  
                if (empty($currentLocalizedProduct) || $productId != $currentLocalizedProduct->id) {
                    $currentLocalizedProduct = new \Walltwisters\model\LocalizedProduct();
                    $currentLocalizedProduct->id = $productId;
                    $currentLocalizedProduct->productDescription = ProductDescription::create($productId, $languageobj->id, $description, $name);
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
        throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
    
    public function getProduct($productid) {                                                              
        $stmt = self::$conn->prepare("SELECT id, formats_id, artist_designer_id, added_by_user_id FROM products where id=?");         
        $stmt->bind_param("i",$productid);                                                              
        $res = $stmt->execute();                                                                        
        if ($res) {
            $stmt->bind_result($id, $formatsId, $artistDesignerId, $addedByUserId);
            $okfetch = $stmt->fetch();
            if ($okfetch) {  
                $product = Product::create($id, $artistDesignerId, $addedByUserId, $formatsId);
                return $product;                                                              
            }
        } 
        throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }  

    public function getProductDescriptionById($id) {                                                              
        $stmt = self::$conn->prepare("SELECT p.id, p.description, l.id as lang_id, l.language "
                . "FROM product_descriptions p INNER JOIN languages l ON l.id = p.language_id where id=?");        
        $stmt->bind_param("i", $id);                                                              
        $res = $stmt->execute();                                                                        
        if ($res) {
            $stmt->bind_result($id, $description, $lang_id, $language);
            $okfetch = $stmt->fetch();
            if ($okfetch) {  
                $language = new Language();
                $language->setId($lang_id);
                $language->setLanguageName($language);
                
                $productDescription = new ProductDescription();
                $productDescription->id = $id;
                $productDescription->descriptionText = $description;
                $productDescription->language = $language;
                return $productDescription;                                                              
            }
        } 
        throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }                                                                                                    
   
   public function getCompleteProduct($productid){
        $sql = "SELECT pd.description, pd.name, im.id as image_id 
           FROM products p
           INNER JOIN product_descriptions pd ON pd.product_id = p.id
           INNER JOIN products_images pi ON pi.product_id = p.id
           INNER JOIN images im ON im.id = pi.image_id
           WHERE p.id = $productid";
        $result = self::$conn->query($sql);                                                                        
        if ($result === FALSE) {
            throw new Exception(self::$conn->error);
        }
        $showRoomProduct = null;
        $lastDescriptionId = null;
        $lastImageId = null;

        while ($row = $result->fetch_assoc()){
            if (empty($showRoomProduct)) {
                $showRoomProduct = new \Walltwisters\viewmodel\ShowRoomProduct();
                $showRoomProduct->name = $row['name'];
            }
            if (!empty($row['description_id']) && $row['description_id'] != $lastDescriptionId) {
                $showRoomProduct->addDescription($row['description']);
                $lastDescriptionId = $row['description_id'];
            }
            if (!empty($row['image_id']) && $row['image_id'] != $lastImageId) {
                $showRoomProduct->addImageId($row['image_id']);
                $lastImageId = $row['image_id'];
            }

        }
        return $showRoomProduct;   
    }
    
   
        
        public function addSlider($slider){
            $stmt = self::$conn->prepare("INSERT INTO slider_text(image_id, product_id,
                                              sales_message, titel, added_by_user_id)
                                              VALUES(?, ?, ?, ?, ?)");
            $stmt->bind_param("iissi", $image_id, $product_id, $salesmessage, $titel, $added_by_user);
            $product_id = $slider->productid;
            $image_id = $slider->imageid;
            $salesmessage = $slider->salesmessage;
            $titel = $slider->titel;
            $added_by_user = $slider->userid;
            $res = $stmt->execute();
            if ($res) {
                $lastIdRes = self::$conn->query("SELECT LAST_INSERT_ID()");
                $row = $lastIdRes->fetch_row();                                       
                $lastId = $row[0];   
               return $lastId;
            }
           throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
       
        }
        
        public function getShowSlider($sliderId) {
            $sql = ("SELECT sales_message, titel, image_id FROM slider_text WHERE id = ?");
            $stmt = self::$conn->prepare($sql);
            $stmt->bind_param('i', $sliderId);
            $res = $stmt->execute();
            if ($res){
                $stmt->bind_result($salesMessage, $title, $imageId);
                if ($stmt->fetch()) {
                    return ['sales_message' => $salesMessage, 'title' => $title, 'image_id' => $imageId];
                }
            }
            return false;
        }
        
       
}