<?php
namespace Walltwisters\service;

use Walltwisters\utilities\Images;
use Walltwisters\model\ProductItem;
use Walltwisters\viewmodel\ProductListRow;
use Walltwisters\viewmodel\ShowRoomProduct;

class ProductService extends BaseService {
    
    public function __construct($repositoryFactory) {
        parent::__construct($repositoryFactory);
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
    
    /**
     * 
     * @param type $imageCategoryValues
     * @param type $imageDatas
     * @param type $productinfos
     * @param type $formatId
     * @param type $sectionId
     * @param type $materialIds
     * @param type $sizeMaterialIds
     * @param integer $userId
     * @return type
     */
    public function addProduct(
            $imageCategoryValues,
            $imageDatas,
            $productinfos,
            $formatId,
            $sectionId,
            $materialIds,
            $sizeMaterialIds,
            $userId) {
        $productRepository = $this->repositoryFactory->getRepository('productRepository');
        $imageRepository = $this->repositoryFactory->getRepository('imageRepository');
        $productImageRepository = $this->repositoryFactory->getRepository('productImageRepository');
        $productDescriptionRepository = $this->repositoryFactory->getRepository('productDescriptionRepository');
        $productSectionRepository = $this->repositoryFactory->getRepository('productSectionRepository');
        
        $getId = true;
        $product = $productRepository->create(Walltwisters\model\Product::create(0, null, $userId, $userId, $formatId), $getId);
        
        $fileidx = 0;
        foreach ($imageDatas as $imagefile){
            $imageCategoryValue = $imageCategoryValues[$fileidx];
            $imageName = $imagefile['name'][0];
            $filepath = $imagefile["tmp_name"][0];
            $mime = $imagefile["type"][0];
            $size = $imagefile["size"][0];
            $image = Walltwisters\model\Image::create($filepath, $size, $mime, $imageName, $imageCategoryValue);
            $imageId = $imageRepository->addImage($image);
            $productImageRepository->create(Walltwisters\model\ProductImage::create($product->id, $imageId));
            $fileidx++;
        }
      
        $productdescriptions = $this->getDescriptionsToSave($productinfos, $product->id);
        foreach ($productdescriptions as $productdescription){
            $productDescriptionRepository->create($productdescription);
        }
        $productSectionRepository->create(Walltwisters\model\ProductSection::create($product->id, $sectionId));
        
        $this->saveProductItems($product->id, $materialIds, $sizeMaterialIds);
        
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
    
    private function saveProductItems($productId, $materialIds, $sizeMaterialIds, $updateExisting = false) {
        $items = [];
        $itemRepo = $this->repositoryFactory->getRepository('itemRepository');
        $productItemRepo = $this->repositoryFactory->getRepository('productItemRepository');
        foreach($sizeMaterialIds as $materialId => $sizeIds) {
            if (!isset($materialIds[$materialId])) {
                continue;
            }
            foreach($sizeIds as $sizeId){
                $item = Walltwisters\model\Item::create($sizeId, $materialId);
                $items = array_merge($items, $itemRepo->getItemsByMaterialSizeId($item));
            }
        }
        if ($updateExisting) {
            $productItemRepo->deleteForid('product_id', $productId);
        }
        foreach ($items as $itemObj){
            $productItemRepo->create(ProductItem::create($productId, $itemObj->id));
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
   
}
