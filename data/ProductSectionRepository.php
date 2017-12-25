<?php

require_once 'data/BaseRepository.php';
require_once 'model/ProductSection.php';

class ProductSectionRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("products_sections", "ProductSection");
    }
    
    protected function getColumnNamesForInsert() {
        return ['product_id', 'section_id'];
    }
    
    protected function getColumnValuesForBind($productSection) {
        $product_id = $productSection->productId;
        $section_id = $productSection->sectionId;
        return [['i', &$product_id], ['i', &$section_id]];
    }
}
