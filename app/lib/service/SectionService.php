<?php
namespace Walltwisters\service;


use Walltwisters\utilities\HtmlUtilities;
use Walltwisters\model\Section;
use Walltwisters\model\Country;
use Walltwisters\model\Language;

class SectionService extends BaseService {
    
    public function __construct($repositoryFactory) {
        parent::__construct($repositoryFactory);
    }
    
    public function getAllSections() {
        $repo = $this->repositoryFactory->getRepository('completeSectionRepository');
        $sections = $repo->getAllCompleteSections();
        
        return $sections;
    }
    
    
    public function getProductsForSection($countryId, $languageId, $sectionId = 0) {
        $productIds = [];
        if($sectionId){
            $productSectionRepo = $this->repositoryFactory->getRepository('productSectionRepository');
            $obj = \Walltwisters\model\ProductSection::create(0, $sectionId, $countryId, $languageId);
            $productIds = $productSectionRepo->getProductsForSectionId($obj);
            if(!is_array($productIds)){
                $productIds = [$productIds];
            }
        }
        $country = Country::create($countryId, '');
        $language = Language::create($languageId, '');
        
        $productRepo = $this->repositoryFactory->getRepository('localizedProductRepository');
        $allLocalizedProducts = $productRepo->getLocalizedProductsByCountryAndLanguage($country, $language);
        $allProducts = $this->jasonizeLocalizedProductsThumbNails($allLocalizedProducts);
        if(!empty($productIds)){
            $localizedProductsInSection = $productRepo->getLocalizedProductsByIds($productIds, $language);
            $sectionProducts = $this->jasonizeLocalizedProductsThumbNails($localizedProductsInSection);
            $allProducts = $this->spliceAllProducts($allProducts, $sectionProducts);
            if(empty($allProducts)){
                $allProducts = [];
            }
            return $response = ['allProducts'=> $allProducts, 'includedProducts' => $sectionProducts];
        }                                                                                
        return $response = ['allProducts'=> $allProducts, 'includedProducts' => []];
    }
    
    public function deleteSection($sectionId){
        $repo = $this->repo();
        $section = new Section();
        $section->id = $sectionId;
        $res = $repo->deleteForId($section);
        
        return $res;
    }
    
    
    public function getCompleteSectionForId($sectionId){
        $repo = $this->repositoryFactory->getRepository('completeSectionRepository');
        $sections = $repo->getCompleteSectionById($sectionId);
        
        return $sections;
    }

    
    public function getAvailableProductsforSection($country, $language, $json = false){
        $countryTypeCheck = is_a($country, '\Walltwisters\model\Country');
        if(!$countryTypeCheck) {
          $country =  \Walltwisters\model\Country::create($country, '');
        }
        $languageTypeCheck = is_a($language, '\Walltwisters\model\Language');
        if(!$languageTypeCheck) {
          $language =  \Walltwisters\model\Language::create($language, '');
        }
        $productRepo = $this->repositoryFactory->getRepository('productRepository');
        $localizedProducts = $productRepo->getLocalizedProductsByCountryAndLanguageOrIds($country, $language);
        if(!$json){
            return $this->createLocalizedThumbnails($localizedProducts);
        
        }
        return $this->jasonizeLocalizedProductsThumbNails($localizedProducts);
               
    }
    
    public function getCompleteSectionsById($id){
        $sectionrepo = $this->repo();
        $sections = $sectionrepo->getCompleteSectionsById($id);
        
        
        return $sections;
    }
    
    public function initializeSection($userId){
        $sectionrepo = $this->repo();
        $section = $sectionrepo->create(Section::create(null ,null ,null, $userId), true);
        
        return $section->id;
    }
    
    public function updateSectionCopy($sectionId, $languageId, $title, $saleslineHeader, $saleslineParagraph, $description){
        $sectionDescriptionRepo = $this->repositoryFactory->getRepository('sectionDescriptionRepository');
        $sectionObj = \Walltwisters\model\SectionDescription::create($title,$saleslineHeader,$saleslineParagraph, $description, $languageId, $sectionId);        
                                                                                          
        if(empty($title) && empty($saleslineHeader) && empty($saleslineParagraph) && empty($description)){
            $sectionDescriptionRepo->deleteForId($sectionObj);
            return $sectionId;
        }
        
        $sectionDescription = $sectionDescriptionRepo->createOrUpdate($sectionObj);
        return $sectionDescription->sectionId;
    }
    
    public function updateProductIdsForMarket($sectionId, $countryId,  $languageId, $ids) {
        $productSectionRepo = $this->repositoryFactory->getRepository('productSectionRepository');
        $obj = \Walltwisters\model\ProductSection::create(0, $sectionId, $countryId, $languageId);
        $productSectionRepo->deleteForId($obj);
        foreach( $ids as $id) {
            if(!empty($id)){
                $obj = \Walltwisters\model\ProductSection::create($id, $sectionId, $countryId, $languageId);
                $productSectionRepo->create($obj);
            }
        }

        return $sectionId;
    }
    
    private function spliceAllProducts($allProducts, $sectionProducts) {
        foreach($sectionProducts as  $product1){
            foreach($allProducts as $ind => $product2){
                if( $product1['productid'] === $product2['productid'] &&
                    $product1['name'] === $product2['name'] &&
                    $product1['image_id'] === $product2['image_id']
                ){
                  unset($allProducts[$ind]);  
                }
            }
        }
        
        return array_values($allProducts);
    }
    
    private function repo(){
        return  $this->repositoryFactory->getRepository('sectionRepository');
    }
    
    private function jasonizeLocalizedProductsThumbNails($localizedProducts){
        $jsonProducts = [];
        $jasonProduct = ['productid' => 0 , 'name' => '', 'image_id' => 0];
        foreach($localizedProducts as $localizedproduct){
            $jasonProduct['productid'] = $localizedproduct->id;
            $jasonProduct['name'] = $localizedproduct->productDescription->name;
            foreach($localizedproduct->imageBaseInfos as $image){
                if( $image->category === 'product'){
                $jasonProduct['image_id'] = $image->id ;
                }
                
            }
            array_push($jsonProducts, $jasonProduct);
        }
        return $jsonProducts;
    }
    
}
