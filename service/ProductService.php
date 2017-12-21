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
            $productName,
            $productDescriptionText,
            $format,
            $category,
            $subcategory,
            $languageId,
            $size,
            $material,
            $technique,
            $countryindexes,
            $userId) {
        $product = new Product(0, $productName, $userId);
        $product = $this->productRepository->addProduct($product);
        foreach ($imageDatas as $key => $imagedata) {
            $image = new Image($imagedata['filepath'], $imagedata['size'], $imagedata['mime'], $productName, $key == 0 ? 'product' : 'productinterior');
            $imageId = $this->imageRepository->addImage($image);
            $this->productRepository->addProductImage($product->id, $imageId);
        }
        $productDescription = new ProductDescription();
        $productDescription->product = $product;
        $productDescription->descriptionText = $productDescriptionText;
        $language = new Language();
        $language->id = $languageId;
        $productDescription->language = $language;
        $productDescription->country = Country::create($countryindexes[0], '');
        $this->productRepository->addProductDescriptionToProduct($productDescription);
        foreach ($countryindexes as $countryindex){
            $country = Country::create($countryindex, '');
            $this->productRepository->addCountryForProduct($country, $product);
        }
        $this->productRepository->addProductCategorySubCategory($product->getId(), $category, $subcategory);
        $this->productRepository->addItem($product->getId(), $size, $material, $technique);
        
        return $product->id;
    }
}
