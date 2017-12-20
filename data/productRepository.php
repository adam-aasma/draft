<?php

require_once 'BaseRepository.php';
require_once 'model/Product.php';
require_once 'model/ProductDescription.php';
require_once 'model/Language.php';
require_once 'model/User.php';
require_once 'model/Country.php';
require_once 'model/Format.php';
require_once 'model/ProductSize.php';
require_once 'model/ProductMaterial.php';
require_once 'model/ProductCategory.php';
require_once 'model/PrintTechnique.php';
require_once 'viewmodel/ShowRoomProduct.php';
require_once 'model/Slider.php';

class ProductRepository extends BaseRepository {
    // adding product to database
    public function addProduct(Product $product) {
        $stmt = $this->conn->prepare("INSERT INTO products(name, added_by_user_id) VALUES(?, ?)");
        $pro_name = $product->getProductName();
        $userid = $product->userid;
        
        $stmt->bind_param("si", $pro_name, $userid);
        
        $res = $stmt->execute();
        if ($res) {
            $lastIdRes = $this->conn->query("SELECT LAST_INSERT_ID()");         
            $row = $lastIdRes->fetch_row();                                       
            $lastId = $row[0];                                                       
            return new Product($lastId, $pro_name,'',$userid);
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
    
    public function addProductDescriptionToProduct($productDescription) {
        $stmt = $this->conn->prepare("INSERT INTO product_descriptions(products_id, language_id, country_id, description) VALUES(?,?,?,?)");
        $productId = $productDescription->product->getId();
        $languageId = $productDescription->language->id;
        $countryId =  $productDescription->country->id;
        $description = $productDescription->descriptionText;
        
        $stmt->bind_param("iiis", $productId, $languageId, $countryId, $description);
        
        $res = $stmt->execute();
        if ($res) {
            $lastIdRes = $this->conn->query("SELECT LAST_INSERT_ID()");         
            $row = $lastIdRes->fetch_row();                                       
            $lastId = $row[0];   
            $productDescription->id = $lastId;      
            return $productDescription;
        }
        throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        
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

    public function addCountryForProduct ($country,$product )  {
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



public function getFormat() {                                                              
        $sql = ("SELECT * FROM formats");         
        $result = $this->conn->query($sql);                                                                        
            if ($result === FALSE) {
                throw new Exception($this->conn->error);
            }
            $formats = [];
          
            while ($row = $result->fetch_object('Format')){
                $formats[] = $row;
            }
            if (empty($formats)) {
                throw new Exception('failed');
            }
            return $formats;                                       
            
    } 
    
    public function getSize() {                                                              
        $sql = ("SELECT * FROM sizes");         
        $result = $this->conn->query($sql);                                                                        
            if ($result === FALSE) {
                throw new Exception($this->conn->error);
            }
            $sizes = [];
          
            while ($row = $result->fetch_object('Size')){
                $sizes[] = $row;
            }
            if (empty($sizes)) {
                throw new Exception('failed');
            }
            return $sizes;                                       
            
    } 
    public function getTechnique() {                                                              
        $sql = ("SELECT * FROM print_techniques");         
        $result = $this->conn->query($sql);                                                                        
            if ($result === FALSE) {
                throw new Exception($this->conn->error);
            }
            $techniques = [];
          
            while ($row = $result->fetch_object('PrintTechnique')){
                $techniques[] = $row;
            }
            if (empty($techniques)) {
                throw new Exception('failed');
            }
            return $techniques;                                       
           
    } 
    public function getMaterial() {                                                              
        $sql = ("SELECT * FROM materials");         
        $result = $this->conn->query($sql);                                                                        
            if ($result === FALSE) {
                throw new Exception($this->conn->error);
            }
            $materials = [];
          
            while ($row = $result->fetch_object('ProductMaterial')){
                $materials[] = $row;
            }
            if (empty($materials)) {
                throw new Exception('failed');
            }
            return $materials;                                       
          
    } 
    public function getProductCategory() {                                                              
        $sql = ("SELECT * FROM products_categories");         
        $result = $this->conn->query($sql);                                                                        
            if ($result === FALSE) {
                throw new Exception($this->conn->error);
            }
            $categories = [];
          
            while ($row = $result->fetch_object('ProductCategory')){
                $categories[] = $row;
            }
            if (empty($categories)) {
                throw new Exception('failed');
            }
            return $categories;                                       
        
    } 
    public function getProductSubCategory() {                                                              
        $sql = ("SELECT * FROM products_subcategories");         
        $result = $this->conn->query($sql);                                                                        
            if ($result === FALSE) {
                throw new Exception($this->conn->error);
            }
            $subcategories = [];
          
            while ($row = $result->fetch_object('ProductSubCategory')){
                $subcategories[] = $row;
            }
            if (empty($subcategories)) {
                throw new Exception('failed');
            }
            return $subcategories;                                       
        
    } 
    public function getLanguages() {                                                              
        $sql = ("SELECT * FROM languages");         
        $result = $this->conn->query($sql);                                                                        
            if ($result === FALSE) {
                throw new Exception($this->conn->error);
            }
            $languages = [];
          
            while ($row = $result->fetch_object('Language')){
                $languages[] = $row;
            }
            if (empty($languages)) {
                throw new Exception('failed');
            }
            return $languages;                                       
        
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
   public function addItem($productId, $sizeIds, $materialIds, $printTechniqueIds){
        $stmt = $this->conn->prepare("INSERT INTO items(product_id, size_id, material_id, print_technique_id) VALUES(?, ?, ?, ?)");
        $stmt->bind_param("iiii", $product_id, $size_id, $material_id, $print_technique_id);
        foreach($sizeIds as $sizeId) {
            foreach($materialIds as $materialId) {
                foreach($printTechniqueIds as $printTechniqueId){
                    $size_id = $sizeId;
                    $product_id = $productId;
                    $material_id = $materialId;
                    $print_technique_id = $printTechniqueId;
                    $res = $stmt->execute();
                    if (!$res) {
                        throw new Exception($stmt->error);
                    }
               }
           }
       }
   }
   
   public function getCompleteProduct($productid){
        $sql = "SELECT p.name, pd.id as description_id, pd.description, im.id as image_id 
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
    
    public function getProductList(){
        $sql = "SELECT id, name FROM products";
        $result = $this->conn->query($sql);
        if ($result === FALSE) {
            throw new Exception($this->conn->error);
        }
        $product = ['id' => 0, 'name' => ''];
        $productlist = [];
        while ($row =$result->fetch_assoc()){
        $product['id'] = $row['id'];
        $product['name'] = $row['name'];
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
        $stmt = $this->conn->prepare("INSERT INTO slider_text(image_id, product_id, language_id, country_id,
                                          sales_message, titel, added_by_user_id) VALUES(?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiissi", $image_id, $product_id, $language_id, $country_id, $salesmessage, $titel, $added_by_user);
            $product_id = $slider->productid;
            $image_id = $slider->imageid;
            $country_id = $slider->countryid;
            $language_id = $slider->languageid;
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