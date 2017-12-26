<?php
require_once 'interfacesrepo/IRepositoryFactory.php';
require_once 'productRepository.php';
require_once 'LanguageRepository.php';
require_once 'CountryRepository.php';
require_once 'PrivilegeRepository.php';
require_once 'ProductFormatRepository.php';
require_once 'ProductPrintTechniqueRepository.php';
require_once 'SectionRepository.php';
require_once 'ProductDescriptionRepository.php';
require_once 'ProductImageRepository.php';
require_once 'ProductSectionRepository.php';
require_once 'ProductMaterialRepository.php';
require_once 'ProductSizeRepository.php';
require_once 'ItemRepository.php';
require_once 'ImageRepository.php';

class RepositoryFactory implements IRepositoryFactory {            
    private $productRepository;
    private $languageRepository;
    private $countryRepository;
    private $privilegeRepository;
    private $productFormatRepository;
    private $productPrintTechniqueRepository;
    private $sectionRepository;
    private $productDescriptionRepository;
    private $productImageRepository;
    private $productSectionRepository;
    private $productMaterialRepository;
    private $productSizeRepository;
    private $itemRepository;
    private $imageRepository;
    private static $repositoryFactory;
    
    public static function getInstance() {
        if (empty(self::$repositoryFactory)) {
            self::$repositoryFactory = new RepositoryFactory();
        }
        return self::$repositoryFactory;
    }
    
    public function getRepository($repositoryName) {
        return $this->__get($repositoryName);
    }
    
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
                case 'sectionRepository':
                    $this->sectionRepository = new SectionRepository();
                    break;
                case 'productDescriptionRepository':
                    $this->productDescriptionRepository = new ProductDescriptionRepository();
                    break;
                case 'productImageRepository':
                    $this->productImageRepository = new ProductImageRepository();
                    break;
                case 'productSectionRepository':
                    $this->productSectionRepository = new ProductSectionRepository();
                    break;
                case 'productMaterialRepository':
                    $this->productMaterialRepository = new ProductMaterialRepository();
                    break;
                case 'productSizeRepository':
                    $this->productSizeRepository = new ProductSizeRepository();
                    break;
                case 'itemRepository':
                    $this->itemRepository = new ItemRepository();
                    break;
                case 'imageRepository':
                    $this->imageRepository = new ImageRepository();
                    break;
            }
        }
        return $this->$name;
    }
}
