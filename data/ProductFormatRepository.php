<?php
require_once 'data/BaseRepository.php';
require_once 'model/ProductFormat.php';


class ProductFormatRepository extends BaseRepository {
    
    public function __construct() {
        parent::__construct("formats", "format");
    }
    
    public function getAllFormats() {
        return $this->getAllObjects();
    }
    //put your code here
}
