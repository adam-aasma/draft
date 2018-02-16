<?php
namespace Walltwisters\repository; 

use Walltwisters\interfacesrepo\IRepositoryFactory;

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
    private $printerRepository;
    private $itemPriceRepository;
    private $productItemRepository;
    private $imageCategoryRepository;
    private $sectionDescriptionRepository;
    private $sliderRepository;
    private $sliderDescriptionRepository;
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
                case 'printerRepository':
                    $this->printerRepository = new PrinterRepository();
                    break;
                case 'itemPriceRepository':
                    $this->itemPriceRepository = new ItemPriceRepository();
                    break;
                case 'productItemRepository':
                    $this->productItemRepository = new ProductItemRepository();
                    break;
                case 'imageCategoryRepository':
                    $this->imageCategoryRepository = new ImageCategoryRepository();
                    break;
                case 'sectionDescriptionRepository':
                    $this->sectionDescriptionRepository = new SectionDescriptionRepository();
                    break;
                case 'sliderDescriptionRepository':
                    $this->sliderDescriptionRepository = new SliderDescriptionRepository();
                    break;
                case 'sliderRepository' :
                    $this->sliderRepository = new SliderRepository();
                    break;
            }
        }
        return $this->$name;
    }
}
