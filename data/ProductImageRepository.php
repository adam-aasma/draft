<?php

require_once 'model/ProductImage.php';

class ProductImageRepository extends BaseRepository {
    
    public function __construct() {
        parent::__construct("products_images", "ProductImage");
    }
    
    protected function getColumnNamesForInsert() {
        return ['product_id', 'image_id'];
    }
    
    protected function getColumnValuesForBind($productImage) {
        $product_id = $productImage->productId;
        $image_id = $productImage->imageId;
      
        return [['i', &$product_id], ['i', &$image_id]];
    }
}
