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
    
    protected function getColumnNamesForSelect(){
        return ['product_descriptions.name', 'product_descriptions.description',
                'product_descriptions.language_id', 'product_descriptions.country_id, products.id'];
    }
    
    protected function getTableForInnerJoinOn(){
        return ['languages','product_descriptions.products_id = products.id'];
    }

    protected function getWhereCondition($Countries){
        return ['product_descriptions.country_id IN (', $strCountry] ;
    }
}
