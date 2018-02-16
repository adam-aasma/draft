<?php
namespace Walltwisters\repository;

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
    
    public function getImageCategoriesBy($conditions){
        $stmt = $this->createStatementForInClause("SELECT id, category, description FROM images_categories", 'category', $conditions, 's');
        $imageCategories= [];
        $res = $stmt->execute();
        if ($res) {
            $stmt->bind_result($id, $category, $description);
            while ($stmt->fetch()) {
                $imageCategories[] = \Walltwisters\model\ImageCategory::create($id, $category, $description);
            }
        } 
        return $imageCategories;
      
    }
}
