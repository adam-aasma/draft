<?php


namespace Walltwisters\viewmodel;

/**
 * Description of ProductListRow2
 *
 * @author adam
 */
class ProductListRow2 {
    private $id;
    private $names;
    private $descriptions;
    private $pictures;
    private $itemDetails;
    private $markets;
    private $languages;
    
    public function __construct(){
        $this->id = 0;
        $this->names = [];
        $this->descriptions= [];
        $this->pictures = [];
        $this->itemDetails = [];
        $this->markets = [];
        $this->languages = [];
    }
    
    
    public static function create($completeProducts){
        $productListRows = [];
        foreach($completeProducts as $completeProduct){
            $obj = new ProductListRow2();
            $obj->id = $completeProduct->id;
            foreach($completeProduct->productDescriptions as $productDescription){
                $obj->addToProperty($productDescription->name, $productDescription->languageName, $productDescription->languageId, 'names');
                $obj->addToProperty($productDescription->descriptionText, $productDescription->languageName, $productDescription->languageId, 'descriptions');
                $obj->addLanguages($productDescription->languageId);
            }
            foreach($completeProduct->imageBaseInfos as $imageBaseInfo){
                array_push($obj->pictures ,['name' => $imageBaseInfo->imageName,
                                   'category' => $imageBaseInfo->category,
                                   'imageId' => $imageBaseInfo->id,
                                   'categoryId' => $imageBaseInfo->categoryId]);
            }
            foreach($completeProduct->items as $item){
                $obj->itemDetails[$item->countryId] = [ 'country' => $item->country,
                                                        'material' => $item->material,
                                                        'size' => $item->size
                                                       ];
            }
            array_push($productListRows, $obj->jazonize());
        }
        
        return $productListRows;
    }
    
    private function addToProperty($name, $language, $languageId, $property){
        $this->$property[]= ['name' => $name , 'language' => $language , 'languageId' => $languageId];
    }
    
    private function addLanguages($languageId){
        array_push($this->languages ,$languageId);
    }
    
    
    
    
    private function jazonize(){
        return ['id' => $this->id,
                'names' => $this->names,
                'descriptions' => $this->descriptions,
                'pictures' => $this->pictures,
                'itemdetails' => $this->itemDetails,
                'markets' => count($this->itemDetails),
                'languages' => count($this->languages)];
    }
   
}
