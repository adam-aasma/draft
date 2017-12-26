<?php

require_once 'data/productRepository.php';
require_once 'data/productDescriptionRepository.php';
//require_once 'data/'

class ProductListService {
    private $productRepo;
    
    public function __construct(){
        $this->productRepo = new productRepository();
        $this->descriptionRepo = new ProductDescriptionRepository();
    }
    
    public function getProductList($countries){
        $productlist = $this->descriptionRepo->getProductList($countries);
        $productInfos = [];
        foreach ($productlist as $product){
            $productInfos[] = ProductDescription::create($product['id'],
                                                $product['languageid'],
                                                $product['description'],    
                                                $product['countryid'], 
                                                $name = $product['name']);
            }
        return $productInfos;
    }
    
}