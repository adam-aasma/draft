<?php
require_once 'interfacesrepo/IRepositoryFactory.php';

class ProductService {
    private $repositoryFactory;
    
    public function __construct($repositoryFactory) {
        $this->repositoryFactory = $repositoryFactory;
    }
    
    public function getCountryLanguages($countries){
        $languageRepository = $this->repositoryFactory->getRepository('languageRepository');
        $countriesIds = [];
        foreach ($countries as $country){
            $countryId = $country->id;
            $countriesIds[] = $countryId;
        }
        $languages = $languageRepository->getUserLanguages($countriesIds);
        return $languages;
    }
    
    public function getAllMaterials() {
        return $this->repositoryFactory->getRepository('productMaterialRepository')->getAll();
    }
    
    public function getAllSizes() {
        return $this->repositoryFactory->getRepository('productSizeRepository')->getAll();
    }
    
    public function getAllFormats() {
        return $this->repositoryFactory->getRepository('productFormatRepository')->getAll();
    }
    
    public function getAllPrintTechniques() {
        return $this->repositoryFactory->getRepository('productPrintTechniqueRepository')->getAll();
    }
    
    public function getAllSections() {
        return $this->repositoryFactory->getRepository('sectionRepository')->getAll();
    }

    public function addProduct(
            $imageDatas,
            $productinfos,
            $formatId,
            $sectionId,
            $size,
            $material,
            $technique,
            $userId) {
        $productRepository = $this->repositoryFactory->getRepository('productRepository');
        $imageRepository = $this->repositoryFactory->getRepository('imageRepository');
        $productImageRepository = $this->repositoryFactory->getRepository('productImageRepository');
        $productDescriptionRepository = $this->repositoryFactory->getRepository('productDescriptionRepository');
        $productSectionRepository = $this->repositoryFactory->getRepository('productSectionRepository');
        $itemRepository = $this->repositoryFactory->getRepository('itemRepository');
        
        $getId = true;
        $product = $productRepository->save(Product::create(0, null, $userId, $formatId), $getId);
        foreach ($imageDatas as $key => $imagedata) {
            $image = new Image($imagedata['filepath'], $imagedata['size'], $imagedata['mime'], '', $key == 0 ? 'product' : 'productinterior');
            $imageId = $imageRepository->addImage($image);
            $productImageRepository->save(ProductImage::create($product->id, $imageId));
        }
        $productdescriptions = $this->getDescriptionsToSave($productinfos, $product->id);
        foreach ($productdescriptions as $productdescription){
            $productDescriptionRepository->save($productdescription);
        }
        $productSectionRepository->save(ProductSection::create($product->id, $sectionId));
        
        $items = $this->getItemsToSave($product->id, $size, $material, $technique);
        foreach($items as $item) {
            $itemRepository->save($item);
        }
        
        return $product->id;
    }
    
    private function getDescriptionsToSave($productInfos, $productId){
        $productDescriptions = [];
        foreach ( $productInfos as $countryId => $countryinfos ){
            foreach ($countryinfos as $languageId => $languageinfo){
                if (empty($languageinfo['description']) || empty($languageinfo['name'])){
                    continue;
                }
                $description = $languageinfo['description'];
                $name = $languageinfo['name'];
                $productDescriptions[] = ProductDescription::create($productId, $languageId, $description, $countryId, $name);
            }
        }
        return $productDescriptions;
    }
    
    private function getItemsToSave($productId, $sizes, $materials, $printTechniques) {
        $items = [];
        foreach($sizes as $sizeKey => $size) {
            foreach($materials as $materialKey => $material) {
                foreach($printTechniques as $printTechniqueKey => $printTechnique){
                    $items[] = Item::create($productId, $sizeKey, $materialKey, $printTechniqueKey);
               }
           }
       }
       return $items;
    }
}
