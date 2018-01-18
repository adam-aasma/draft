<?php
require_once 'service/BaseService.php';

class SectionService extends BaseService {
    
    public function __construct($repositoryFactory) {
        parent::__construct($repositoryFactory);
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
