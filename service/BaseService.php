<?php
require_once 'interfacesrepo/IRepositoryFactory.php';

class BaseService {
    protected $repositoryFactory;
    
    public function __construct($repositoryFactory) {
        $this->repositoryFactory = $repositoryFactory;
    }
    
    public function getAllMaterials() {
        return $this->repositoryFactory->getRepository('productMaterialRepository')->getAll();
    }
    
    public function getAllSizes() {
        return $this->repositoryFactory->getRepository('productSizeRepository')->getAll();
    }
    
    public function getAllFormats() {
        return $this->repositoryFactory->getRepository('productFormatRepository')->getAll();
    }
    
    public function getAllPrintTechniques() {
        return $this->repositoryFactory->getRepository('productPrintTechniqueRepository')->getAll();
    }
    
    public function getAllSections() {
        return $this->repositoryFactory->getRepository('sectionRepository')->getAll();
    }
    
    public function getImageCategoriesBy($conditions){
        return $this->repositoryFactory->getRepository('imageCategoryRepository')->getImageCategoriesBy($conditions);
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
}
