<?php
namespace Walltwisters\repository;

use Walltwisters\model\ProductItem;

class ProductItemRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("products_items", "Walltwisters\model\ProductItem");
    }
    
    protected function getColumnNamesForInsert() {
        return ['product_id', 'country_id', 'material_id', 'size_id'];
    }
    
    protected function getColumnValuesForBind($productitem) {
        $product_id = $productitem->productId;
        $country_id = $productitem->countryId;
        $material_id = $productitem->materialId;
        $size_id = $productitem->sizeId;
        

        return [['i', &$product_id], ['i', &$country_id], ['i', &$material_id], ['i', &$size_id]];
    }
    
    public function deleteForProductAndCountry($productId, $countryId) {
        $sql = "DELETE FROM products_items WHERE product_id = ? AND country_id = ?";
        $stmt = self::$conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("SQL syntax: " . $sql);
        }
        $stmt->bind_param("ii", $product_id, $country_id);
        $product_id = $productId;
        $country_id = $countryId;
        $res = $stmt->execute();
        if (!$res) {
           throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }
    }
}
