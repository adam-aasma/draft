<?php

class ProductService {
    private $productRepository;
    private $languageRepository;
    private $imageRepository;
    private $itemRepository;
    private $productImageRepository;
    private $productSectionRepository;
    private $productDescriptionRepository;
    
    public function __construct(
            $productRepository,
            $itemRepository,
            $languageRepository,
            $imageRepository,
            $productImageRepository,
            $productDescriptionRepository,
            $productSectionRepository) {
        
        $this->productRepository = $productRepository;
        $this->itemRepository = $itemRepository;
        $this->languageRepository = $languageRepository;
        $this->imageRepository = $imageRepository;
        $this->productImageRepository = $productImageRepository;
        $this->productSectionRepository = $productSectionRepository;
        $this->productDescriptionRepository = $productDescriptionRepository;
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
        $getId = true;
        $product = $this->productRepository->save(Product::create(0, null, $userId, $formatId), $getId);
        foreach ($imageDatas as $key => $imagedata) {
            $image = new Image($imagedata['filepath'], $imagedata['size'], $imagedata['mime'], '', $key == 0 ? 'product' : 'productinterior');
            $imageId = $this->imageRepository->addImage($image);
            $this->productImageRepository->save(ProductImage::create($product->id, $imageId));
        }
        $productdescriptions = $this->getDescriptionsToSave($productinfos, $product->id);
        foreach ($productdescriptions as $productdescription){
            $this->productDescriptionRepository->save($productdescription);
        }
        $this->productSectionRepository->save(ProductSection::create($product->id, $sectionId));
        
        $items = $this->getItemsToSave($product->id, $size, $material, $technique);
        foreach($items as $item) {
            $this->itemRepository->save($item);
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
