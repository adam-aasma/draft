<?php
namespace Walltwisters\repository; 

class ProductDescriptionRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("product_descriptions", "Walltwisters\model\ProductDescription");
    }
    
    protected function getColumnNamesForInsert() {
        return ['language_id', 'product_id', 'description', 'name'];
    }
    
    protected function getColumnValuesForBind($productDescription) {
        $language_id = $productDescription->languageId;
        $product_id = $productDescription->productId;
        $description = $productDescription->descriptionText;
        $name = $productDescription->name;
        return [['i', &$language_id], ['i', &$product_id], ['s', &$description], ['s', &$name]];
    }
    
    public function getAllDescriptions() {
        return $this->getAll();
    }
    
    public function getProductList($languages){
        $languageIds = [];
        foreach ($languages as $language){
            $languageIds[] = $language->id;
        }
        $strLanguage = join (',', $languageIds);
        $sql = "SELECT pd.name, pd.description, pd.product_id
                FROM  product_descriptions as pd
                WHERE pd.language_id IN ($strLanguage)";
        $result = self::$conn->query($sql);
        if ($result === FALSE) {
            throw new Exception(self::$conn->error);
        }
        $product = ['product_id' => 0, 'name' => '', 'description' => ''];
        $productlist = [];
        while ($row =$result->fetch_assoc()){
            $product['product_id'] = $row['product_id'];
            $product['name'] = $row['name'];
            $product['description'] = $row['description'];
            $productlist[] = $product;
        }

        return !empty($productlist) ? $productlist : false;
    }
}
