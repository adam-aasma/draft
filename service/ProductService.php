<?php

class ProductService {
    private $productRepository;
    private $languageRepository;
    private $imageRepository;
    private $itemRepository;
    
    public function __construct(
            $productRepository,
            $itemRepository,
            $languageRepository,
            $imageRepository) {
        
        $this->productRepository = $productRepository;
        $this->itemRepository = $itemRepository;
        $this->languageRepository = $languageRepository;
        $this->imageRepository = $imageRepository;
    }
    
    public function addProduct(
            $imageDatas,
            $productinfos,
            $format,
            $category,
            $subcategory,
            $size,
            $material,
            $technique,
            $userId) {
        $product = new Product(0, $format, $userId);
        $product = $this->productRepository->addProduct($product);
        foreach ($imageDatas as $key => $imagedata) {
            $image = new Image($imagedata['filepath'], $imagedata['size'], $imagedata['mime'], '', $key == 0 ? 'product' : 'productinterior');
            $imageId = $this->imageRepository->addImage($image);
            $this->productRepository->addProductImage($product->id, $imageId);
        }
        $this->productRepository->addProductDescriptionToProduct($productinfos, $product->id);
        $this->productRepository->addProductCategorySubCategory($product->getId(), $category, $subcategory);
        
        $items = $this->getItemsToSave($product->getId(), $size, $material, $technique);
        foreach($items as $item) {
            $this->itemRepository->save($item);
        }
        
        return $product->id;
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
