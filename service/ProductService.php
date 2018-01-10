<?php
require_once 'interfacesrepo/IRepositoryFactory.php';
require_once 'viewmodel/ProductListRow.php';
require_once 'library/Images.php';

use Walltwisters\model\ProductItem;

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
    
    public function getImageCategoriesBy(){
        return $this->repositoryFactory->getRepository('imageCategoryRepository')->getImageCategoriesBy();
    }

    public function addProduct(
            $imageCategoryValues,
            $imageDatas,
            $productinfos,
            $formatId,
            $sectionId,
            $sizeMaterialIds,
            $userId) {
        $productRepository = $this->repositoryFactory->getRepository('productRepository');
        $imageRepository = $this->repositoryFactory->getRepository('imageRepository');
        $productImageRepository = $this->repositoryFactory->getRepository('productImageRepository');
        $productDescriptionRepository = $this->repositoryFactory->getRepository('productDescriptionRepository');
        $productSectionRepository = $this->repositoryFactory->getRepository('productSectionRepository');
        $itemRepository = $this->repositoryFactory->getRepository('itemRepository');
        
        $getId = true;
        $product = $productRepository->save(Walltwisters\model\Product::create(0, null, $userId, $formatId), $getId);
        
        $fileidx = 0;
        foreach ($imageDatas as $imagefile){
            $imageCategoryValue = $imageCategoryValues[$fileidx];
            $filepath = $imagefile["tmp_name"][0];
            $mime = $imagefile["type"][0];
            $size = $imagefile["size"][0];
            $image = Walltwisters\model\Image::create($filepath, $size, $mime, $imageCategoryValue);
            $imageId = $imageRepository->addImage($image);
            $productImageRepository->save(Walltwisters\model\ProductImage::create($product->id, $imageId));
            $fileidx++;
        }
       foreach( $imageCategoryValues as $imageCategoryValue){
         }
        
        $productdescriptions = $this->getDescriptionsToSave($productinfos, $product->id);
        foreach ($productdescriptions as $productdescription){
            $productDescriptionRepository->save($productdescription);
        }
        $productSectionRepository->save(Walltwisters\model\ProductSection::create($product->id, $sectionId));
        
        $this->getItemsToSave($product->id, $sizeMaterialIds, $itemRepository);
        
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
                $productDescriptions[] = Walltwisters\model\ProductDescription::create($productId, $languageId, $description, $countryId, $name);
            }
        }
        return $productDescriptions;
    }
    
    private function getItemsToSave($productId, $sizeMaterialIds, $itemRepo) {
        $items = [];
        $productItemRepo = $this->repositoryFactory->getRepository('productItemRepository');
        foreach($sizeMaterialIds as $materialId => $sizeIds) {
            foreach($sizeIds as $sizeId){
                $item = Walltwisters\model\Item::create($sizeId, $materialId);
                $items = array_merge($items, $itemRepo->getItemsByMaterialSizeId($item));
            }
        }
        foreach ($items as $itemObj){
            $productItemRepo->save(ProductItem::create($productId, $itemObj->id));
        }
        
       
    } 
    public function getProductById($id) {
        $repo = $this->repositoryFactory->getRepository('productRepository');
        $completeProduct = $repo->getCompleteProductById($id);
        return $completeProduct;
    }
    
    public function getList($country, $language){
        $repo = $this->repositoryFactory->getRepository('productRepository');
        $products = $repo->getLocalizedProductsByCountryAndLanguage($country, $language);
        $productListRows = [];
        foreach($products as $product) {
            $productListRow = new ProductListRow();
            $productListRow->productId = $product->id;
            $productListRow->name = $product->productDescription->name;
            $productListRow->description = $product->productDescription->descriptionText;
            foreach ($product->items as $item) {
                $productListRow->addItemDetails($item->sizes, $item->material, $item->printTechnique);
            }
            foreach ($product->imageBaseInfos as $imageBaseInfo) {
                $productListRow->addImage($imageBaseInfo->id, $imageBaseInfo->name);
            }
            
            $productListRows[] = $productListRow;
        }
        
        return $productListRows;
    }
    
    public function getSizesForMaterial($material){
        $repo = $this->repositoryFactory->getRepository('itemRepository');
        return $repo->getItemSizesBy($material);
       
    }
    
    public function getCompeteProductBy($id){
        $repo = $this->repositoryFactory->getRepository('productRepository');
        $showRoomProduct = $repo->getCompleteProduct($id);
        
        return $showRoomProduct;
    }
   
}
