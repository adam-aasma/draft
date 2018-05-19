<?php
namespace Walltwisters\lib\repository; 

use Walltwisters\lib\model\ItemSize;

class ItemSizeRepository extends Baserepository {
    public function __construct() {
        parent::__construct("sizes", "Walltwisters\lib\model\ItemSize");
    }
    
    protected function getColumnNamesForInsert() {
        return ['sizes', 'name'];
    }
    
    protected function getColumnValuesForBind($size) {
        $sizes = $size->sizes;
        $name = $size->name;
        

        return [['s', &$sizes], ['s', &$name]];
    }
    
    public function checkIfExists($size){
        $sql = "SELECT id FROM sizes WHERE sizes = ?";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("s", $size);                                                              
        $res = $stmt->execute();
         if ($res) {
            $stmt->bind_result($id);
            $stmt->fetch();
        }
        
        if(!$id){
            $this->create(ItemSize::create($size, ''));
            
        }
        return $id;
    
    }
    
    protected function createObjArray($stmt) {
        $stmt->bind_result($id, $size, $name);
        $objCollection = [];
        while($stmt->fetch()){
          $objCollection[]  = ItemSize::create($size, $name, $id);
        }
        return $objCollection;
    }
    
   
    
} 
