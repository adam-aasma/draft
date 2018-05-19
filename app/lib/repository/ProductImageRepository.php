<?php
namespace Walltwisters\lib\repository; 

use Walltwisters\lib\model\ProductImage;


class ProductImageRepository extends BaseRepository {
    
    public function __construct() {
        parent::__construct("products_images", "Walltwisters\lib\model\ProductImage");
    }
    
    protected function getColumnNamesForInsert() {
        return ['product_id', 'image_id'];
    }
    
    protected function getColumnValuesForBind($productImage) {
        $product_id = $productImage->productId;
        $image_id = $productImage->imageId;
      
        return [['i', &$product_id], ['i', &$image_id]];
    }
    
    public function getImagesIdBy($id){
        $stmt = self::$conn->prepare("SELECT image_id FROM products_images pi
                                      WHERE pi.product_id = ?");
                                      
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id);
        $stmt->fetch();
        
        return ['size' => $size, 'mime' => $mimetype, 'data' => $data];
    }
}
