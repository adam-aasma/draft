<?php
require_once 'productRepository.php';
require_once 'LanguageRepository.php';

class RepositoryFactory {
    private $productRepository;
    private $languageRepository;
    
    public function __get($name) {
        if (empty($this->$name)) {
            switch($name) {
                case 'productRepository':
                    $this->productRepository = new ProductRepository();
                    break;
                case 'languageRepository':
                    $this->languageRepository = new LanguageRepository();
                    break;
            }
        }
        return $this->$name;
    }
}
