<?php
require_once 'productRepository.php';
require_once 'LanguageRepository.php';
require_once 'CountryRepository.php';
require_once 'PrivilegeRepository.php';
require_once 'ProductFormatRepository.php';
require_once 'ProductPrintTechniqueRepository.php';
require_once 'ProductCategoryRepository.php';
require_once 'ProductDescriptionRepository.php';
require_once 'ProductMaterialRepository.php';
require_once 'ProductSizeRepository.php';
require_once 'ProductSubCategoryRepository.php';

class RepositoryFactory {            
    private $productRepository;
    private $languageRepository;
    private $countryRepository;
    private $privilegeRepository;
    private $productFormatRepository;
    private $productPrintTechniqueRepository;
    private $productCategoryRepository;
    private $productDescriptionRepository;
    private $productMaterialRepository;
    private $productSizeRepository;
    private $productSubCategoryRepository;
    
    

    public function __get($name) {
        if (empty($this->$name)) {
            switch($name) {
                case 'productRepository':
                    $this->productRepository = new ProductRepository();
                    break;
                case 'languageRepository':
                    $this->languageRepository = new LanguageRepository();
                    break;
                case 'countryRepository':
                    $this->countryRepository = new CountryRepository();
                    break;
                case 'privilegeRepository':
                    $this->privilegeRepository = new PrivilegeRepository();
                    break;
                case 'productFormatRepository':
                    $this->productFormatRepository = new ProductFormatRepository();
                    break;
                case 'productPrintTechniqueRepository':
                    $this->productPrintTechniqueRepository = new ProductPrintTechniqueRepository();
                    break;
                case 'productCategoryRepository':
                    $this->productCategoryRepository = new ProductCategoryRepository();
                    break;
                case 'productDescriptionRepository':
                    $this->productDescriptionRepository = new ProductDescriptionRepository();
                    break;
                case 'productMaterialRepository':
                    $this->productMaterialRepository = new ProductMaterialRepository();
                    break;
                case 'productSizeRepository':
                    $this->productSizeRepository = new ProductSizeRepository();
                    break;
                case 'productSubCategoryRepository':
                    $this->productSubCategoryRepository = new ProductSubCategoryRepository();
                    break;
                
                
            }
        }
        return $this->$name;
    }
}
