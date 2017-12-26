<?php

require_once 'data/BaseRepository.php';
require_once 'model/ProductDescription.php';

class ProductDescriptionRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("product_descriptions", "ProductDescription");
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
    
     public function getProductList($countries){
        $countryids = [];
        foreach ($countries as $country){
            $countryids[] = $country->id;
        }
        $strCountry = join(',', $countryids);
        $sql = "SELECT pd.name, pd.description, pd.language_id, pd.country_id, products.id
                FROM  product_descriptions as pd
                INNER JOIN products ON pd.product_id = products.id WHERE pd.country_id IN ($strCountry)";
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
}
