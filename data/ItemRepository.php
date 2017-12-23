<?php
require_once 'BaseRepository.php';
require_once 'model/Item.php';

class ItemRepository  extends BaseRepository {
    
    public function __construct() {
        parent::__construct("items", "Item");
    }
    
    function getColumnNamesForInsert() {
        return ['product_id', 'size_id', 'material_id', 'print_technique_id'];
    }
    
    function getColumnValuesForBind($item) {
        $product_id = $item->productId;
        $size_id = $item->sizeId;
        $material_id = $item->materialId;
        $print_technique_id = $item->printTechniqueId;

        return [['i', &$product_id], ['i', &$size_id], ['i', &$material_id], ['i', &$print_technique_id]];
    }
}
