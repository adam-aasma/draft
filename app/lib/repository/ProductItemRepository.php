<?php
namespace Walltwisters\repository;

use Walltwisters\model\ProductItem;

class ProductItemRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("products_items", "Walltwisters\model\ProductItem");
    }
    
    protected function getColumnNamesForInsert() {
        return ['product_id', 'item_id'];
    }
    
    protected function getColumnValuesForBind($productitem) {
        $product_id = $productitem->productId;
        $item_id = $productitem->itemId;
        

        return [['i', &$product_id], ['i', &$item_id]];
    }
}
