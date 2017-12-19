<?php

require_once 'BaseRepository.php';
require_once 'model/Image.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ImageRepository
 *
 * @author adam
 */
class ImageRepository extends BaseRepository {
    
    public function getCategories() {
        
        $sql = "SELECT id, name FROM categories";
        $result = $this->conn->query($sql);
        if ($result === FALSE) {
            throw new DatabaseException($this->conn->error);
        }
        $keysAndValues =[];
        $row= $result->fetch_assoc();
        while ($row){
            $keysAndValues[$row['id']] = $row['name'];
            $row=$result->fetch_assoc();
        }
        return $keysAndValues;
    }
    
    public function getAllImageIds() {
        $sql = "SELECT id FROM images";
        $result = $this->conn->query($sql);  // om det går fel false annars ett objekt som hette mysql result
        if ($result === FALSE) {
            throw new DatabaseException($this->conn->error);
        }

        $ids = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $ids[] = $row["id"];
            }
        }
        return $ids;
    }
    
    public function getImagesByCategory($category) {
         $sql = "SELECT id FROM images where category_id='" . $category . "'";
         $result = $this->conn->query($sql);

         $ids = [];
         if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $ids[] = $row["id"];
            }
        }
        return $ids;
        
    }
    
    public function addImage($image) {
        $categories = $this->getImagesCategories();
        
        $data = file_get_contents($image->getFilepath());
        $stmt = $this->conn->prepare("INSERT INTO images(data,name,mimetype,images_category_id,size) VALUES(?, ?, ?, ?, ?)");
        $null = NULL;
        try {
            $bindresult = $stmt->bind_param("bsssi", $null, $imgname, $mimetype, $imgcategory_id, $imgsize);
        } catch(Exception $e) {
            var_dump($e);
        }
        $imgname = $image->getName();
        $mimetype = $image->getMime();
        $imgcategory_id = 1;  // TODO change this later added this table slider/product/productinterior
        foreach($categories as $id => $category) {
            if ($image->getCategory() == $category) {
                $imgcategory_id = $id;
                break;
            }
        }
        $imgsize = $image->getSize();
        $stmt->send_long_data(0, $data);

        $res = $stmt->execute();
        if ($res) {
            $lastIdRes = $this->conn->query("SELECT LAST_INSERT_ID()");
            $row = $lastIdRes->fetch_row();
            $lastId = $row[0];
            return $lastId;
        }
        throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
    
    private function getImagesCategories(){
        $sql = ("SELECT id, category FROM images_categories"); 
        $result = $this->conn->query($sql);
        $imagecategories = [];
        while ($row = $result->fetch_assoc()){
            $imagecategories[$row['id']] = $row['category'];
        }
        if (empty($imagecategories)) {
            return false;
        }
        return $imagecategories;
    }
    
    public function getImage($id) {
        $stmt = $this->conn->prepare("SELECT size,mimetype,data FROM images WHERE id=?"); 
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($size,$mimetype,$data);
        $stmt->fetch();
        
        return ['size' => $size, 'mime' => $mimetype, 'data' => $data];
    }
    
    
    
}
    
    