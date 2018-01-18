<?php
namespace Walltwisters\data;

require_once 'model/ImageCategory.php';

class ImageCategoryRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("images_categories", "Walltwisters\model\ImageCategory");
    }
   
    protected function getColumnNamesForInsert() {
        return "not implemented";
    }
    
    protected function getColumnValuesForBind($itemprice) {
        return "not implemented";
    }
    
   public function getImageCategoriesBy($condition1='product', $condition2='productinterior' , $condition3=''){
        $stmt = $this->conn->prepare("SELECT id, category, description FROM images_categories WHERE category=? OR category=? OR category=?");         
        $stmt->bind_param("sss",$condition1, $condition2, $condition3);                                                              
        $res = $stmt->execute(); 
        $imageCategories= [];
        if ($res) {
            $stmt->bind_result($id, $category, $description);
            while ($stmt->fetch()) {
                $imageCategories[] = \Walltwisters\model\ImageCategory::create($id, $category, $description);
            }
        } 
        return $imageCategories;
      
    }
}
