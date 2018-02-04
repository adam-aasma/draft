<?php
namespace Walltwisters\repository; 

require_once 'data/BaseRepository.php';
require_once 'model/ProductDescription.php';

class ProductDescriptionRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("product_descriptions", "Walltwisters\model\ProductDescription");
    }
    
    protected function getColumnNamesForInsert() {
        return ['language_id', 'product_id', 'description', 'country_id', 'name'];
    }
    
    protected function getColumnValuesForBind($productDescription) {
        $language_id = $productDescription->languageId;
        $product_id = $productDescription->productId;
        $description = $productDescription->descriptionText;
        $name = $productDescription->name;
        $country_id = $productDescription->countryId;
        return [['i', &$language_id], ['i', &$product_id], ['s', &$description], ['i', &$country_id], ['s', &$name]];
    }
    
    public function getAllDescriptions() {
        return $this->getAll();
    }
    
     public function getProductList($countries, $languages){
        $countryIds = [];
        foreach ($countries as $country){
            $countryIds[] = $country->id;
        }
        $languageIds = [];
        foreach ($languages as $language){
            $languageIds[] = $language->id;
        }
        $strCountry = join(',', $countryIds);
        $strLanguage = join (',', $languageIds);
        $sql = "SELECT pd.name, pd.description, pd.product_id
                FROM  product_descriptions as pd
                WHERE pd.country_id IN ($strCountry) AND pd.language_id IN ($strLanguage)";
        $result = $this->conn->query($sql);
        if ($result === FALSE) {
            throw new Exception($this->conn->error);
        }
        $product = ['product_id' => 0, 'name' => '', 'description' => ''];
        $productlist = [];
        while ($row =$result->fetch_assoc()){
        $product['product_id'] = $row['product_id'];
        $product['name'] = $row['name'];
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
}
