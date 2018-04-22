<?php
namespace Walltwisters\service;


use Walltwisters\model\Image;
use Walltwisters\model\ProductImage;




class ImageService extends BaseService {
    
    public function __construct($repositoryFactory) {
        parent::__construct($repositoryFactory);
    }
    
    //add productImage to db
    
    public function addImage($imageDatas, $imageCategoryId, $id, $category = 'product') {
        $productImageRepo = $this->repositoryFactory->getRepository('productImageRepository');
        foreach($imageDatas as $imageData){
         $imageId = $this->prepareAndSave($imageData, $imageCategoryId);
         switch( $category){
             case 'product' :
                $productImageRepo = $this->repositoryFactory->getRepository('productImageRepository');
                $productImageRepo->create(ProductImage::create($id, $imageId));
                break;
             case 'section' :
                 $sectionRepo = $this->repositoryFactory->getRepository('sectionRepository');
                 $sectionId = $sectionRepo->updateSection($id, $imageId, $imageCategoryId);
                 
                 break;
         }
        }
        return $imageId;
       
    } 
    private function prepareAndSave($imageData, $imageCategoryId){
       $image= $this->getImageData($imageData);
       $image->categoryId = $imageCategoryId;
       $imageRepo = $this->repositoryFactory->getRepository('imageRepository'); 
       $id = $imageRepo->addImage($image);
       
       return $id;
    }
    private function getImageData($imagefile){
        $filepath = $imagefile["tmp_name"][0];
        $mime = $imagefile["type"][0];
        $size = $imagefile["size"][0];
        $name = $imagefile["name"][0];
        $image = Image::create($filepath, $size, $mime, $name);
        return $image;
    }
    
    
    public function addSectionImages($sectionPictures) {
       $imageCategories = $this->getImageCategoriesBy('sectionImageCategories');
       foreach ($imageCategories as $imagecategory){
           if($imagecategory->category == 'sectionbig'){
               $bigSectionPicId = $imagecategory->id;
           }
           else if ($imagecategory->category == 'sectionsmall'){
               $smallSectionPicId = $imagecategory->id;
           }
           else if ($imagecategory->category == 'sectionmobile'){
               $mobileSectionPicId = $imagecategory->id;
           }
       }
       extract($sectionPictures);
       $imageRepo = $this->repositoryFactory->getRepository('imageRepository');
       $bigPicId = $this->addImageHelper($desktopbig, $bigSectionPicId);
       $smallPicId = $this->addImageHelper($desktopsmall, $smallSectionPicId);
       $mobilePicId = $this->addImageHelper($mobile, $mobileSectionPicId);
       
       return ['bigpicid' => $bigPicId, 'smallpicid' => $smallPicId, 'mobilepicid' =>$mobilePicId];
       
    }
    
    public function getProductImageIdById($id){
        $imageRepo = $this->repositoryFactory->getRepository('imageRepository');    
        $id = $imageRepo->getImageIdByProductId($id);
        
        return $id;

    }
    
    public function deleteImage($id) {
        $imageRepo = $this->repositoryFactory->getRepository('imageRepository');    
        return $imageRepo->deleteImageForId($id);
    }
}