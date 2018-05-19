<?php
namespace Walltwisters\lib\repository; 

use Walltwisters\lib\interfacesrepo\IRepositoryFactory;

class RepositoryFactory implements IRepositoryFactory {            
    private $productRepository;
    private $languageRepository;
    private $countryRepository;
    private $privilegeRepository;
    private $productFormatRepository;
    private $itemPrintTechniqueRepository;
    private $sectionRepository;
    private $productDescriptionRepository;
    private $productImageRepository;
    private $productSectionRepository;
    private $itemMaterialRepository;
    private $itemSizeRepository;
    private $itemRepository;
    private $imageRepository;
    private $printerRepository;
    private $itemPriceRepository;
    private $productItemRepository;
    private $imageCategoryRepository;
    private $sectionDescriptionRepository;
    private $sliderRepository;
    private $sliderDescriptionRepository;
    private $artistDesignerRepository;
    private $localizedProductRepository;
    private $completeProductRepository;
    private $completeSectionRepository;
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
                case 'itemPrintTechniqueRepository':
                    $this->itemPrintTechniqueRepository = new ItemPrintTechniqueRepository();
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
                case 'itemMaterialRepository':
                    $this->itemMaterialRepository = new ItemMaterialRepository();
                    break;
                case 'itemSizeRepository':
                    $this->itemSizeRepository = new ItemSizeRepository();
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
                case 'artistDesignerRepository':
                    $this->artistDesignerRepository = new ArtistDesignerRepository();
                    break;
                case 'localizedProductRepository':
                    $this->localizedProductRepository = new LocalizedProductRepository();
                    break;
                case 'completeProductRepository':
                    $this->completeProductRepository = new CompleteProductRepository();
                    break;
                case 'completeSectionRepository':
                    $this->completeSectionRepository = new CompleteSectionRepository();
                    break;
            }
        }
        return $this->$name;
    }
}
