<?php
require_once 'service/BaseService.php';
require_once 'viewmodel/SectionListRow.php';
require_once 'library/HtmlUtilities.php';

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
    
    public function getAvailableProductsforSection($country, $language){
        $productRepo = $this->repositoryFactory->getRepository('productRepository');
        $localizedProducts = $productRepo->getLocalizedProductsByCountryAndLanguage($country, $language);
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
        $sectionrepo = $this->repositoryFactory->getRepository('sectionRepository');
        $sections = $sectionrepo->getAllSectionsByCountryLanguage($country, $language);
        
        return $sections;
    }
    
    public function getCompleteSectionsById($id){
        $sectionrepo = $this->repositoryFactory->getRepository('sectionRepository');
        $sections = $sectionrepo->getCompleteSectionsById($id);
        
        
        return $sections;
    }
}
