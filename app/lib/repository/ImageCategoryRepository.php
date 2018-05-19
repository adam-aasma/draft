<?php
namespace Walltwisters\lib\repository;

class ImageCategoryRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("images_categories", "\Walltwisters\lib\model\ImageCategory");
    }
   
    protected function getColumnNamesForInsert() {
        return "not implemented";
    }
    
    protected function getColumnValuesForBind($itemprice) {
        return "not implemented";
    }
    
    public function getImageCategoriesBy($string){
        $stmt = $this->createStatementForInClause("SELECT id, category, description FROM images_categories", 'category', $this->imageCategories($string), 's');
        $imageCategories= [];
        $res = $stmt->execute();
        if ($res) {
            $stmt->bind_result($id, $category, $description);
            while ($stmt->fetch()) {
                $imageCategories[] = \Walltwisters\lib\model\ImageCategory::create($id, $category, $description);
            }
        } 
        return $imageCategories;
      
    }
    
    private function imageCategories($string){
        $condition = [];
        switch ($string){
            case 'productImageCategories':
                $condition = ['product', 'productinterior'];
                break;
            case 'sectionImageCategories':
                $condition = ['sectionsmall', 'sectionbig', 'sectionmobile'];
                break;
            
        }
        return $condition;
    }
    
    public function getCategoryNameById($id){
        $sql = ("SELECT category FROM images_categories WHERE id = ?  ");
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $res = $stmt->execute();
        if ($res) {
            $stmt->bind_result($categoryName);
            $stmt->fetch();
            $category = $categoryName;
        }
        
        return $category;
    }
}
