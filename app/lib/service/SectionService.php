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
    

/*    
    public function addSection($sectionInfos, $imageIds, $createdByUserId, $countryId, $languageId, $productIds) {
        $sectionrepo = $this->repositoryFactory->getRepository('sectionRepository');
        $sectionDescriptionRepo = $this->repositoryFactory->getRepository('sectionDescriptionRepository');
        $sectionId = $sectionrepo->create(Walltwisters\model\Section::create($imageIds['bigpicid'], $imageIds['smallpicid'], $imageIds['mobilepicid'], $createdByUserId), true)->id;
        $sectionDescription = Walltwisters\model\SectionDescription::create($sectionInfos['title'], $sectionInfos['saleslineheader'], $sectionInfos['saleslineparagraph'], $countryId, $languageId, $sectionId);
        $sectionDescriptionRepo->create($sectionDescription);
        if( $productIds){
            $productsectionrepo = $this->repositoryFactory->getRepository('productSectionRepository');
            foreach ($productIds as $productId){
                $productsectionrepo->create(\Walltwisters\model\ProductSection::create($productId, $sectionId));
            }
        }
        return $sectionId;
    }
 * 
 */
    
    /*
    public function getSectionListBy($country, $language) {
        $sectionrepo = $this->repositoryFactory->getRepository('sectionRepository');
        $completeSections = $sectionrepo->getCompleteSectionBy($country, $language);
        $sectionListRows = [];
        foreach ($completeSections as $completeSection){
            $sectionListRow = \Walltwisters\viewmodel\SectionListRow::create($completeSection->id, $completeSection->titel, $completeSection->salesLineHeader, $completeSection->salesLineParagraph, $completeSection->languageId);
            foreach ($completeSection->imageBaseInfos as $imageBaseInfo){
                if($imageBaseInfo->category == 'sectionbig' || $imageBaseInfo->category == 'sectionsmall'){
                    $sectionListRow->addDesktopImageId($imageBaseInfo->id);
                }
                else if($imageBaseInfo->category == 'sectionmobile'){
                    $sectionListRow->addMobileImageId($imageBaseInfo->id);
                }
            }
            foreach ($completeSection->productIds as $productId){
                $sectionListRow->addProductId($productId);
            }
            $sectionListRows[] = $sectionListRow;
        }
        
        
        return $sectionListRows;;
    }
    */
    
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
    
 /*   
    public function getSelectedproductsById($Ids, bool $post = false){
        $productRepo = $this->repositoryFactory->getRepository('productRepository');
        $productThumbNails = '';
        $products = [];
        foreach($Ids as $id){
            $product = $productRepo->getCompleteProductById($id);
            foreach ($product->productDescriptions as $productDescriptions){
                foreach ($productDescriptions as $productDescription){
                $name = $productDescription->name;
                }
            }
            foreach ( $product->imageBaseInfos as $imageBaseInfo){
               if( $imageBaseInfo->category === 'product'){
                    $imageId = $imageBaseInfo->id;
                }
            
            }
            if (!$post){
              $productThumbNails .= HtmlUtilities::createThumbNail($product->id, $name, $imageId);
            } else if($post){
                $productThumbNails .= HtmlUtilities::createThumbNail($product->id, $name, $imageId, 'products[]');
            }
        }
        return $productThumbNails;
    } 
   
  * 
  */
    
    /*
    public function getAllSectionNamesBy($country, $language){
        $sectionrepo = $this->repo();
        $sections = $sectionrepo->getAllSectionsByCountryLanguage($country, $language);
        
        return $sections;
    }
     * 
     */
    
    
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
    
    /*
    private function createLocalizedThumbnails($localizedProduct){
        $productThumbNails = '';
            foreach ($localizedProducts as $localizedProduct){
                foreach($localizedProduct->imageBaseInfos as $imageBaseInfo){
                    if( $imageBaseInfo->category === 'product'){
                        $productThumbNails .= HtmlUtilities::createThumbNail($localizedProduct->id, $localizedProduct->productDescription->name, $imageBaseInfo->id );
                    }
                }
            }
            return $productThumbNails;
    }
     * 
     */
    
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
