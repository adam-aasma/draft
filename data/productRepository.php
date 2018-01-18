<?php
namespace Walltwisters\data; 

use Walltwisters\model\CompleteProduct;
use Walltwisters\viewmodel\ShowRoomProduct;

require_once 'BaseRepository.php';
require_once 'model/Product.php';
require_once 'model/LocalizedProduct.php';
require_once 'model/CompleteProduct.php';
require_once 'model/ProductDescription.php';
require_once 'model/Language.php';
require_once 'model/User.php';
require_once 'model/Country.php';
require_once 'model/ProductFormat.php';
require_once 'model/ProductSize.php';
require_once 'model/ProductMaterial.php';
require_once 'model/Section.php';
require_once 'model/ProductPrintTechnique.php';
require_once 'viewmodel/ShowRoomProduct.php';
require_once 'viewmodel/ProductInfoView.php';
require_once 'model/Slider.php';



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
        , it.id as item_id
        , sz.id as size_id
        , sz.sizes
        , ma.id as material_id
        , ma.material
        , pt.id as print_technique_id
        , pt.technique
        , im.id as image_id
        , im.image_name
        , ic.id as image_category_id
        , ic.category as image_category
        , im.size as image_size
        ,form.format as product_format_name
        ,p.formats_id as product_format_id
        ,sec.id as section_id
        ,sec.titel as section_name
        FROM products p
        LEFT JOIN product_descriptions pd ON pd.product_id = p.id
        INNER JOIN languages la ON la.id = pd.language_id
        INNER JOIN countries co ON co.id = pd.country_id
        INNER JOIN products_items pitem ON pitem.product_id = p.id
        LEFT JOIN items it ON it.id = pitem.item_id
        LEFT JOIN sizes sz ON sz.id = it.size_id
        LEFT JOIN materials ma ON ma.id = it.material_id
        LEFT JOIN print_techniques pt ON pt.id = it.print_technique_id
        LEFT JOIN products_images pi ON pi.product_id = p.id
        INNER JOIN images im ON im.id = pi.image_id
        INNER JOIN images_categories ic ON ic.id = im.images_category_id
        LEFT JOIN products_sections p_sec ON p_sec.product_id = p.id
        LEFT JOIN sections sec ON sec.id = p_sec.section_id
         INNER JOIN formats as form ON form.id = p.formats_id
        WHERE p.id = ?
        ORDER BY co.id, la.id
        ";
        $stmt = $this->conn->prepare($sql);
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
                    $itemId, 
                    $sizeId, 
                    $size, 
                    $materialId, 
                    $material, 
                    $techniqueId, 
                    $technique, 
                    $imageId,
                    $imageName,
                    $imageCategoryId,
                    $imageCategory, 
                    $imageSize,
                    $productFormatName,
                    $productFormatId,
                    $productSectionId,
                    $productSectionName
                    );
            $okfetch = $stmt->fetch();
            if ($okfetch) {
                $product = new CompleteProduct();
                $product->id = $productId;
                $product->formatId = $productFormatId;
                $product->formatName = $productFormatName;
                $product->sectionId = $productSectionId;
                $product->sectionName = $productSectionName;
                $countryAndLang = null;
                $imagesHandled = [];
                $itemsHandled = [];
                while ($okfetch) {
                    $okfetch = $stmt->fetch();
                    if ($countryAndLang != $countryId . '_' . $languageId) {
                        $countryAndLang = $countryId . '_' . $languageId;
                        $product->addProductDescription(
                                \Walltwisters\model\ProductDescription::createExtended($productId, $languageId, $language, $description, $countryId, $country, $name));
                    }
                    if (!array_key_exists($itemId, $itemsHandled)){
                        $product->addItem(\Walltwisters\model\Item::createExtended($itemId, $productId, $sizeId, $size, $materialId, $material, $techniqueId, $technique));
                        $itemsHandled[$itemId] = true;
                    }
                
                    if (!array_key_exists($imageId, $imagesHandled)) {
                        if ($imageSize > 0) {
                            $product->addImageBaseInfo(\Walltwisters\model\ImageBaseInfo::createBaseInfo($imageId, $imageName, $imageCategoryId, $imageCategory));
                        }
                        $imagesHandled[$imageId] = true;
                    }
                }
                
                return $product;
            }
        }
        throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
    
    public function getLocalizedProductsByCountryAndLanguage($country, $language) {
        $sql = "SELECT p.id as product_id
        , pd.description
        , pd.name 
        , it.id as item_id
        , sz.id as size_id
        , sz.sizes
        , ma.id as material_id
        , ma.material
        , pt.id as print_technique_id
        , pt.technique
        , im.id as image_id
        , im.size as image_size
        , im.image_name as image_name
        , imc.id as image_category_id
        , imc.category as image_category
        , it.printer_id as printer_id
        FROM products p
        LEFT JOIN product_descriptions pd ON pd.product_id = p.id
        LEFT JOIN products_items pri ON pri.product_id = p.id
        LEFT JOIN items it ON it.id = pri.item_id
        LEFT JOIN sizes sz ON sz.id = it.size_id
        LEFT JOIN materials ma ON ma.id = it.material_id
        LEFT JOIN print_techniques pt ON pt.id = it.print_technique_id
        LEFT JOIN products_images pi ON pi.product_id = p.id
        INNER JOIN images im ON im.id = pi.image_id
        LEFT JOIN images_categories imc ON imc.id =im.images_category_id
        WHERE pd.country_id = ? AND pd.language_id = ?
        ORDER BY p.id, it.id, im.id
        ";
        $stmt = $this->conn->prepare($sql);     
        $countryId = $country->id;
        $languageId = $language->id;
        $stmt->bind_param("ii", $countryId, $languageId);                                                              
        $res = $stmt->execute();                                                                        
        if ($res) {
            $stmt->bind_result($productId, $description, $name, $itemId, $sizeId, $size, $materialId, $material, $techniqueId, $technique, $imageId, $imageSize, $imageName, $imageCategoryId, $imageCategory, $printerId);
            $okfetch = $stmt->fetch();
            $currentLocalizedProduct = null;
            $localizedProducts = [];
            while ($okfetch) {  
                if (empty($currentLocalizedProduct) || $productId != $currentLocalizedProduct->id) {
                    $currentLocalizedProduct = new \Walltwisters\model\LocalizedProduct();
                    $currentLocalizedProduct->id = $productId;
                    $currentLocalizedProduct->productDescription = \Walltwisters\model\ProductDescription::create($productId, $language->id, $description, $country->id, $name);
                    $localizedProducts[] = $currentLocalizedProduct;
                    $imagesHandled = [];
                    $itemsHandled = [];
                }
                if (!array_key_exists($itemId, $itemsHandled)){
                    $currentLocalizedProduct->addItem(\Walltwisters\model\Item::createExtended($itemId, $productId,  $sizeId, $size, $materialId, $material, $techniqueId, $technique, $printerId));
                    $itemsHandled[$itemId] = true;
                }
                
                if (!array_key_exists($imageId, $imagesHandled)) {
                    if ($imageSize > 0) {
                        $currentLocalizedProduct->addImageBaseInfo(\Walltwisters\model\ImageBaseInfo::createBaseInfo($imageId, $imageName, $imageCategoryId, $imageCategory));
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
        $stmt = $this->conn->prepare("SELECT id, formats_id, artist_designer_id, added_by_user_id FROM products where id=?");         
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
        $stmt = $this->conn->prepare("SELECT p.id, p.description, l.id as lang_id, l.language "
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
        $result = $this->conn->query($sql);                                                                        
        if ($result === FALSE) {
            throw new Exception($this->conn->error);
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
            $stmt = $this->conn->prepare("INSERT INTO slider_text(image_id, product_id,
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
                $lastIdRes = $this->conn->query("SELECT LAST_INSERT_ID()");
                $row = $lastIdRes->fetch_row();                                       
                $lastId = $row[0];   
               return $lastId;
            }
           throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
       
        }
        
        public function getShowSlider($sliderId) {
            $sql = ("SELECT sales_message, titel, image_id FROM slider_text WHERE id = ?");
            $stmt = $this->conn->prepare($sql);
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