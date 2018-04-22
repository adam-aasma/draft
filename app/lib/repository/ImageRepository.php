<?php
namespace Walltwisters\repository; 

use Walltwisters\model\Image;

class ImageRepository extends BaseRepository {
    
    
    function __construct() {
        parent::__construct("images", "Walltwisters\model\Image");
    }
    protected function getColumnNamesForInsert() {
        throw new Exception("Not implemented");
    }
    
    protected function getColumnValuesForBind($aggregate) {
        throw new Exception("Not implemented");
    }
    
    public function addImage(Image $image) {
        $data = file_get_contents($image->filepath);
        $stmt = self::$conn->prepare("INSERT INTO images(data,mimetype,size,images_category_id, image_name) VALUES(?, ?, ?, ?, ?)");
        $null = NULL;
        try {
            $bindresult = $stmt->bind_param("bssis", $null, $mimetype, $imgsize, $imgcategory_id, $imageName);
        } catch(Exception $e) {
            var_dump($e);
        }
        $mimetype = $image->mime;
        $imgsize = $image->size;
        $imgcategory_id = $image->categoryId;
        $imageName = $image->imageName;
        $stmt->send_long_data(0, $data);

        $res = $stmt->execute();
        if ($res) {
            $lastIdRes = self::$conn->query("SELECT LAST_INSERT_ID()");
            $row = $lastIdRes->fetch_row();
            $lastId = $row[0];
            return $lastId;
        }
        throw new \Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
    
    public function getImage($id) {
        $stmt = self::$conn->prepare("SELECT size,mimetype,data FROM images WHERE id=?"); 
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($size,$mimetype,$data);
        $stmt->fetch();
        
        return ['size' => $size, 'mime' => $mimetype, 'data' => $data];
    }
    
    public function getImageIdByProductId($id){
        $stmt = self::$conn->prepare("SELECT i.id FROM images i
                                      INNER JOIN products_images pi ON pi.product_id = ?
                                      INNER JOIN images_categories ic ON ic.id = i.images_category_id
                                      WHERE ic.category = 'product'");
                                      
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id);
        $stmt->fetch();
        
        return $id;
    }
    
    public function deleteImageForId($id) {
        $image = new image();
        $image->id = $id;
        $affectedRow = $this->deleteForId($image);
        
        return $affectedRow;
    }
}
    
    