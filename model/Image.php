<?php

class Image {
    private $filepath;
    private $size;
    private $mime;
    private $name;
    private $category;
    
    function __construct($filepath, $size, $mime, $name, $category) {
        $this->filepath = $filepath;
        $this->size = $size;
        $this->mime = $mime;
        $this->name = $name;
        $this->category = $category;
    }
    
    public function getFilepath() {
        return $this->filepath;
    }
    
    public function getSize() {
        return $this->size;
    }
    
    public function getMime() {
        return $this->mime;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getCategory() {
        return $this->category;
    }
}


