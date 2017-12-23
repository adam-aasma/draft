<?php

require_once 'BaseRepository.php';
require_once 'model/Product.php';
require_once 'model/ProductDescription.php';
require_once 'model/Language.php';
require_once 'model/User.php';
require_once 'model/Country.php';
require_once 'model/ProductFormat.php';
require_once 'model/ProductSize.php';
require_once 'model/ProductMaterial.php';
require_once 'model/ProductCategory.php';
require_once 'model/ProductPrintTechnique.php';
require_once 'viewmodel/ShowRoomProduct.php';
require_once 'model/Slider.php';

class ProductRepository extends BaseRepository {
    function getColumnNamesForInsert() {
        throw new Exception("Not implemented");
    }
    
    function getColumnValuesForBind($aggregate) {
        throw new Exception("Not implemented");
    }
    
    // adding product to database
    public function addProduct(Product $product) {
        $stmt = $this->conn->prepare("INSERT INTO products(formats_id, added_by_user_id) VALUES(?, ?)");
        $userid = $product->userid;
        $formatid =$product->formatid;
        $stmt->bind_param("ii", $formatid, $userid);
        
        $res = $stmt->execute();
        if ($res) {
            $lastIdRes = $this->conn->query("SELECT LAST_INSERT_ID()");         
            $row = $lastIdRes->fetch_row();                                       
            $lastId = $row[0];                                                       
            return new Product($lastId, $formatid ,$userid);
        }
        throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
    
    public function addProductImage($productid, $imageid){
        $stmt = $this->conn->prepare("INSERT INTO products_images(product_id, image_id) VALUES(?, ?)");
        $stmt->bind_param("ii", $productid, $imageid);
        $res = $stmt->execute();
        if ($res) {
            return true;
        }
        throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
    
    public function addProductDescriptionToProduct($productInfos, $productId) {
            foreach ( $productInfos as $countryId => $countryinfos ){
                foreach ($countryinfos as $languageId => $languageinfo){
                    if (empty($languageinfo['description']) || empty($languageinfo['name'])){
                        continue;
                    }
                    $description = $languageinfo['description'];
                    $name = $languageinfo['name'];
                    $stmt = $this->conn->prepare("INSERT INTO product_descriptions(products_id, language_id, country_id, description, name) VALUES(?,?,?,?, ?)");
                    $stmt->bind_param("iiiss", $productId, $languageId, $countryId, $description, $name);
                    $res = $stmt->execute();
                    if (!$res){
                       throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error); 
                }
            }
        }     
        return true;
            
    } 
    
    public function getProduct($productid) {                                                              
        $stmt = $this->conn->prepare("SELECT id, name FROM products where id=?");         
        $stmt->bind_param("i",$productid);                                                              
        $res = $stmt->execute();                                                                        
        if ($res) {
            $stmt->bind_result($id, $name);
            $okfetch = $stmt->fetch();
            if ($okfetch) {  
                $product = new Product($id, $name);
                return $product;                                                              
            }
        } 
        throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }  

    public function addCountryForProduct ($country, $product )  {
         $stmt = $this->conn->prepare("INSERT INTO countries_products(countries_id, product_id) VALUES(?, ?)");
         $stmt->bind_param("ii", $country_id, $product_id);
         $country_id = $country->id;
         $product_id = $product->getId();
         $res = $stmt->execute();
         if ($res) {
                 
            return true;
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

    public function addProductCategorySubCategory($productId, $productCategoryId, $productSubCategoryId){
         $stmt = $this->conn->prepare("INSERT INTO products_categories_subcategories(product_id, category_id, subcategory_id) VALUES(?, ?, ?)");
         $stmt->bind_param("iii", $product_id, $category_id, $sub_category_id);
         $product_id = $productId;
         $category_id = $productCategoryId;
         $sub_category_id = $productSubCategoryId;
         $res = $stmt->execute();
         if ($res) {
                 
            return true;
        }
        throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
         
    }
   public function addItem($productId, $sizes, $materials, $printTechniques){
        $stmt = $this->conn->prepare("INSERT INTO items(product_id, size_id, material_id, print_technique_id) VALUES(?, ?, ?, ?)");
        $stmt->bind_param("iiii", $product_id, $size_id, $material_id, $print_technique_id);
        foreach($sizes as $sizeKey => $size) {
            foreach($materials as $materialKey => $material) {
                foreach($printTechniques as $printTechniqueKey => $printTechnique){
                    $size_id = $sizeKey;
                    $product_id = $productId;
                    $material_id = $materialKey;
                    $print_technique_id = $printTechniqueKey;
                    $res = $stmt->execute();
                    if (!$res) {
                        throw new Exception($stmt->error);
                    }
               }
           }
       }
   }
   
   public function getCompleteProduct($productid){
        $sql = "SELECT pd.id as description_id, pd.description, pd.name, im.id as image_id 
           FROM products p
           INNER JOIN product_descriptions pd ON pd.products_id = p.id
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
                $showRoomProduct = new ShowRoomProduct();
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
    
    public function getProductList($countries){
        $countryids = [];
        foreach ($countries as $country){
            $countryids[] = $country->id;
        }
        $strCountry = join(',', $countryids);
        $sql = "SELECT product_descriptions.name, product_descriptions.description, product_descriptions.language_id, product_descriptions.country_id, products.id
                FROM  product_descriptions
                INNER JOIN products ON product_descriptions.products_id = products.id WHERE product_descriptions.country_id IN ($strCountry)";
        $result = $this->conn->query($sql);
        if ($result === FALSE) {
            throw new Exception($this->conn->error);
        }
        $product = ['id' => 0,'languageid' => 0, 'countryid' => 0, 'name' => '', 'description' => ''];
        $productlist = [];
        while ($row =$result->fetch_assoc()){
        $product['id'] = $row['id'];
        $product['name'] = $row['name'];
        $product['countryid'] = $row['country_id'];
        $product['languageid'] = $row['language_id'];
        $product['description'] = $row['description'];
        $productlist[] = $product;
        }
        if ($productlist){
            return $productlist;
        }
        else {
            return false;
        }
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
            $sql = ("SELECT sales_message, titel, image_id FROM slider_text WHERE sliderid = $sliderId");
            $res = $this->conn->query($sql);
            If ($res){
                $slider = $res->fetch_assoc();
                return $slider;
            }
            else {
                return false;
            }
        }
    
}