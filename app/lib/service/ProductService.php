<?php
namespace Walltwisters\service;

use Walltwisters\utilities\Images;
use Walltwisters\model\ProductItem;
use Walltwisters\viewmodel\ProductListRow;
use Walltwisters\viewmodel\ShowRoomProduct;
use Walltwisters\model\Product;
use Walltwisters\model\ProductImage;
use Walltwisters\model\ProductDescription;
use Walltwisters\viewmodel\ProductListRow2;

class ProductService extends BaseService {
    
    public function __construct($repositoryFactory) {
        parent::__construct($repositoryFactory);
    }
    
    public function getAllProducts(){
        error_reporting(E_ALL & ~E_NOTICE);
        $compProductRepo = $this->repositoryFactory->getRepository('completeProductRepository');
        $products = $compProductRepo->getAllCompleteProducts();
        $listRow = ProductListRow2::create($products);
        
        return $listRow;
    }
   
    /**
     * Updates product
     * @param int $productId
     * @param array $imageCategoryValues
     * @param array $imageDatas
     * @param array $productinfos
     * @param int $formatId
     * @param int $sectionId
     * @param array $materialIds
     * @param array $sizeMaterialIds
     * @param int $userId
     */
    public function updateProduct(
            int $productId,
            array $imageCategoryValues,
            array $imageDatas,
            array $productinfos,
            int $formatId,
            int $sectionId,
            array $materialIds,
            array $sizeMaterialIds,
            int $userId) {
        $productRepository = $this->repositoryFactory->getRepository('productRepository');
        $imageRepository = $this->repositoryFactory->getRepository('imageRepository');
        $productImageRepository = $this->repositoryFactory->getRepository('productImageRepository');
        $productDescriptionRepository = $this->repositoryFactory->getRepository('productDescriptionRepository');
        $productSectionRepository = $this->repositoryFactory->getRepository('productSectionRepository');
        
        $product = $productRepository->update(Walltwisters\model\Product::create($productId, null, null, $userId, $formatId));

        $productDescriptionRepository->deleteForId('product_id', $product->id);
        $productdescriptions = $this->getDescriptionsToSave($productinfos, $product->id);
        foreach ($productdescriptions as $productdescription){
            $productDescriptionRepository->create($productdescription);
        }
        $productSectionRepository->deleteForId('product_id', $product->id);
        $productSectionRepository->create(Walltwisters\model\ProductSection::create($product->id, $sectionId));
        
        $this->saveProductItems($product->id, $materialIds, $sizeMaterialIds, true);
    
    }
    
    public function initializeProduct($userId, $formatId){
        $productRepository = $this->repositoryFactory->getRepository('productRepository');
        $artistId = null;
        $getId = true;
        $product = $productRepository->create(Product::create(0, $artistId, $userId, $userId, $formatId), $getId);
        return $product->id;
    }
    
    public function addProduct($imageIds, $formatId, $artistId, $userId){
        $productRepository = $this->repositoryFactory->getRepository('productRepository');
        $productImageRepository = $this->repositoryFactory->getRepository('productImageRepository');
        $getId = true;
        $product = $productRepository->update(Product::create(0, $artistId, $userId, $userId, $formatId), $getId);
        foreach($imageIds as $imageId) {
            $productImageRepository->update(ProductImage::create($product->id, $imageId));
        }  
        return $product->id;
    }
    
    public function addImageToProduct($imageId, $productId) {
        $productImageRepository = $this->repositoryFactory->getRepository('productImageRepository');
        $productImageRepository->create(ProductImage::create($productId, $imageId));
    }
    
    public function addDescriptionToProduct($languageId, $productId, $name, $description) {
        $productDescriptionRepository = $this->repositoryFactory->getRepository('productDescriptionRepository');
        if ( empty($name) && empty($description)){
            $productDescriptionRepository->deleteForIdandLanguageId($productId, $languageId);
            return;
        }
        $productDescription = ProductDescription::create($productId, $languageId, $description, $name);
        $productDescriptionRepository->createOrUpdate($productDescription);
    }
    
    public function sizestojson(){
        $productSizeRepository = $this->repositoryFactory->getRepository('productSizeRepository');
        $sizes = $productSizeRepository->getAll();
        $result = [];
        foreach($sizes as $size) {
            $result[$size->id] = $size->sizes;
        }

        return json_encode($result);
    }
    
    public function materialstojson(){
        $productMaterialRepository = $this->repositoryFactory->getRepository('productMaterialRepository');
        $materials = $productMaterialRepository->getAll();
        $result = [];
        foreach($materials as $material) {
            $result[$material->id] = $material->material;
        }

        return json_encode($result);
    }
    
    public function saveProductItemsForProductAndCountry($productId, $countryId, $materialsWithSizes) {
        $productItemRepo = $this->repositoryFactory->getRepository('productItemRepository');
        $productItemRepo->deleteForProductAndCountry($productId, $countryId);
        foreach($materialsWithSizes as $materialId => $sizeIds) {
            foreach($sizeIds as $sizeId) {
                $productItemRepo->create(ProductItem::create($productId, $countryId, $materialId, $sizeId));
            }
        }
    } 

    public function getProductById($id) {
        $repo = $this->repositoryFactory->getRepository('productRepository');
        $completeProduct = $repo->getCompleteProductById($id);
        return $completeProduct;
    }
    
    public function getList($country, $language){
        $repo = $this->repositoryFactory->getRepository('productRepository');
        $products = $repo->getLocalizedProductsByCountryAndLanguageOrIds($country, $language);
        $productListRows = [];
        foreach($products as $product) {
            $productListRow = new ProductListRow();
            $productListRow->productId = $product->id;
            $productListRow->name = $product->productDescription->name;
            $productListRow->description = $product->productDescription->descriptionText;
            foreach ($product->items as $item) {
                $productListRow->addItemDetails($item->size, $item->material);
            }
            foreach ($product->imageBaseInfos as $imageBaseInfo) {
                $productListRow->addImage($imageBaseInfo->id, $imageBaseInfo->category);
            }
            
            $productListRows[] = $productListRow;
        }
        
        return $productListRows;
    }
    
    public function getSizesForMaterial($material){
        $repo = $this->repositoryFactory->getRepository('itemRepository');
        return $repo->getItemSizesBy($material);
       
    }
    
    public function getShowRoomProductBy($id){
        $completeProduct = $this->getProductById($id);
        $showRoomProduct = new ShowRoomProduct();
        foreach ($completeProduct->productDescriptions as $productDescriptions){
            foreach ( $productDescriptions as $productDescription){
                $productInfo =  \Walltwisters\viewmodel\ProductInfoView::create($productDescription->countryName, $productDescription->languageName, $productDescription->name, $productDescription->descriptionText);
                $showRoomProduct->addInfo($productInfo);
            }
        }
        
        foreach($completeProduct->imageBaseInfos as $imagebaseinfo){
            $showRoomProduct->addImageId($imagebaseinfo->id);
        }
        
        
        return $showRoomProduct;
    }
    
    public function addSection($titel, $salesLine, $imageIds, $createdByUserId, $languageId, $productIds){
        $section = Walltwisters\model\Section::create($titel, $salesLine, $imageIds['bigpicid'], $imageIds['smallpicid'], $imageIds['mobilepicid'], $languageId, $createdByUserId);
        $sectionrepo = $this->repositoryFactory->getRepository('sectionRepository');
        $sectionId = $sectionrepo->create($section, true)->id;
        $productsectionrepo = $this->repositoryFactory->getRepository('productSectionRepository');
        foreach ($productIds as $productId){
            $productsectionrepo->create(\Walltwisters\model\ProductSection::create($productId, $sectionId));
        }
        
        return $sectionId;
    }
    
    public function addArtistDesigner($artist, $artistId){
        $artistRepo = $this->repositoryFactory->getRepository('artistDesignerRepository');
        if($artistId){
            $artistRepo->updateArtistDesigner($artist, $artistId);
            return $artistId;
        }
        $artistDesigner = \Walltwisters\model\artistDesigner::create(null, $artist);
        $artistRepo = $this->repositoryFactory->getRepository('artistDesignerRepository');
        $artistId = $artistRepo->create($artistDesigner, true)->id;
        
        return $artistId;
    }
   
}
