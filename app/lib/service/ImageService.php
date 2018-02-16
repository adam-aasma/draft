<?php
namespace Walltwisters\service;



class ImageService extends BaseService {
    
    public function __construct($repositoryFactory) {
        parent::__construct($repositoryFactory);
    }
    
    private function getImageData($imagefile){
        $filepath = $imagefile["tmp_name"];
        $mime = $imagefile["type"];
        $size = $imagefile["size"];
        $name = $imagefile["name"];
        $image = Walltwisters\model\Image::create($filepath, $size, $mime, $name);
        return $image;
    }
        
    public function addSectionImages($sectionPictures) {
       $imageCategories = $this->getImageCategoriesBy(['sectionbig', 'sectionsmall', 'sectionmobile']);
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
       $bigPicId = $this->addImage($imageRepo,$desktopbig, $bigSectionPicId);
       $smallPicId = $this->addImage($imageRepo,$desktopsmall, $smallSectionPicId);
       $mobilePicId = $this->addImage($imageRepo,$mobile, $mobileSectionPicId);
       
       return ['bigpicid' => $bigPicId, 'smallpicid' => $smallPicId, 'mobilepicid' =>$mobilePicId];
       
    }
    
    private function addImage($imageRepo,$imageArray, $pictureId){
       $image= $this->getImageData($imageArray);
       $image->categoryId = $pictureId;
       $id = $imageRepo->addImage($image);
       
       return $id;
    }
    
    public function getProductImageIdById($id){
        $imageRepo = $this->repositoryFactory->getRepository('imageRepository');    
        $id = $imageRepo->getImageIdByProductId($id);
        
        return $id;

    }
}