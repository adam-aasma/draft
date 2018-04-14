<?php
namespace Walltwisters\service;


use Walltwisters\utilities\HtmlUtilities;
use Walltwisters\model\Section;


class SectionService extends BaseService {
    
    public function __construct($repositoryFactory) {
        parent::__construct($repositoryFactory);
    }
    
    
    
     public function addSection($sectionInfos, $imageIds, $createdByUserId, $countryId, $languageId, $productIds){
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
    
    public function getSectionListBy($country, $language){
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
        $localizedProducts = $productRepo->getLocalizedProductsByCountryAndLanguage($country, $language);
        if(!$json){
            return $this->createLocalizedThumbnails($localizedProducts);
        
        }
        return $this->jasonizeLocalizedProductsThumbNails($localizedProducts);
               
    }
    
    
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
    
    public function getAllSectionNamesBy($country, $language){
        $sectionrepo = $this->repo();
        $sections = $sectionrepo->getAllSectionsByCountryLanguage($country, $language);
        
        return $sections;
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
    
    private function repo(){
        return  $this->repositoryFactory->getRepository('sectionRepository');
    }
    
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
