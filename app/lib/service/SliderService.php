<?php
namespace Walltwisters\lib\service;



class SliderService extends BaseService {
    
    public function __construct($repositoryFactory) {
        parent::__construct($repositoryFactory);
    }
    
    
    public function addSlider($titel, $salesText, $productId, $imageId, $userId){
        $productrep = $this->repositoryFactory->getRepository('productRepository');
        $slider = new Walltwisters\lib\model\Slider($imageId, $productId, $salesText, $titel, $userId);
        $sliderId = $productrep->addSlider($slider);
        
        return $sliderId;
    }
}
