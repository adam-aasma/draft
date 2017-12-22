<?php

class ProductService {
    private $productRepository;
    private $languageRepository;
    private $imageRepository;
    
    public function __construct(
            $productRepository,
            $languageRepository,
            $imageRepository) {
        
        $this->productRepository = $productRepository;
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
        $this->productRepository->addItem($product->getId(), $size, $material, $technique);
        
        return $product->id;
    }
}
