<?php
namespace Walltwisters\service;


use Walltwisters\model\Image;



class ImageService extends BaseService {
    
    public function __construct($repositoryFactory) {
        parent::__construct($repositoryFactory);
    }
    
    private function getImageData($imagefile){
        $filepath = $imagefile["tmp_name"][0];
        $mime = $imagefile["type"][0];
        $size = $imagefile["size"][0];
        $name = $imagefile["name"][0];
        $image = Image::create($filepath, $size, $mime, $name);
        return $image;
    }
    
    public function addProductImage($imageDatas, $imageCategoryId) {
        foreach($imageDatas as $imageData){
         $imageId = $this->addImage($imageData, $imageCategoryId);
        }
         return $imageId;
       
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
       $bigPicId = $this->addImage($desktopbig, $bigSectionPicId);
       $smallPicId = $this->addImage($desktopsmall, $smallSectionPicId);
       $mobilePicId = $this->addImage($mobile, $mobileSectionPicId);
       
       return ['bigpicid' => $bigPicId, 'smallpicid' => $smallPicId, 'mobilepicid' =>$mobilePicId];
       
    }
    
    private function addImage($imageArray, $pictureId){
       $image= $this->getImageData($imageArray);
       $image->categoryId = $pictureId;
       $imageRepo = $this->repositoryFactory->getRepository('imageRepository'); 
       $id = $imageRepo->addImage($image);
       
       return $id;
    }
    
    public function getProductImageIdById($id){
        $imageRepo = $this->repositoryFactory->getRepository('imageRepository');    
        $id = $imageRepo->getImageIdByProductId($id);
        
        return $id;

    }
    
    public function deleteImage($id) {
        $imageRepo = $this->repositoryFactory->getRepository('imageRepository');    
        $imageRepo->delete($id);
    }
}