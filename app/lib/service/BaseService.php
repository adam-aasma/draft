<?php
namespace Walltwisters\lib\service;

class BaseService {
    protected $repositoryFactory;
    
    public function __construct($repositoryFactory) {
        $this->repositoryFactory = $repositoryFactory;
    }
    
    public function getAllMaterials() {
        return $this->repositoryFactory->getRepository('ItemMaterialRepository')->getAll();
    }
    
    public function getAllSizes() {
        return $this->repositoryFactory->getRepository('ItemSizeRepository')->getAll();
    }
    
    public function getAllFormats() {
        return $this->repositoryFactory->getRepository('productFormatRepository')->getAll();
    }
    
    public function getAllPrintTechniques() {
        return $this->repositoryFactory->getRepository('ItemPrintTechniqueRepository')->getAll();
    }
    
    public function getAllSections() {
        return $this->repositoryFactory->getRepository('sectionRepository')->getAll();
    }
    
    public function getImageCategoriesBy($string){
        return $this->repositoryFactory->getRepository('imageCategoryRepository')->getImageCategoriesBy($string);
    }
    
    public function getCountryLanguages($countries){
        $languageRepository = $this->repositoryFactory->getRepository('languageRepository');
        $countriesIds = [];
        foreach ($countries as $country){
            $countryId = $country->id;
            $countriesIds[] = $countryId;
        }
        $languages = $languageRepository->getUserLanguages($countriesIds);
        return $languages;
    }
    
    public function getCountryLanguages2($countries){
        $languageRepository = $this->repositoryFactory->getRepository('languageRepository');
        $countriesIds = [];
        foreach ($countries as $country){
            $countryId = $country->id;
            $countriesIds[] = $countryId;
        }
        $languages = $languageRepository->getCountryLanguages($countriesIds);
        return $languages;
    }
    
    public function getCountryItems($countries) {
        $countryIds = array_map(function ($country) { return $country->id; }, $countries);
        $itemRepository = $this->repositoryFactory->getRepository('itemRepository');
        return $itemRepository->getCountryItems($countryIds);
    }
}
