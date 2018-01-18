<?php
require_once 'service/BaseService.php';

class SliderService extends BaseService {
    
    public function __construct($repositoryFactory) {
        parent::__construct($repositoryFactory);
    }
    
    
    public function addSlider($titel, $salesText, $productId, $imageId, $userId){
        $productrep = $this->repositoryFactory->getRepository('productRepository');
        $slider = new Walltwisters\model\Slider($imageId, $productId, $salesText, $titel, $userId);
        $sliderId = $productrep->addSlider($slider);
        
        return $sliderId;
    }
}
