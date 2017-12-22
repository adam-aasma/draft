<?php
require_once 'interfacesrepo/ILanguageRepository.php';
require_once 'BaseRepository.php';
require_once 'model/Language.php';

class LanguageRepository extends BaseRepository implements ILanguageRepository {
    
    public function __construct() {
        parent::__construct("languages", "Language");
    }
    
    public function getAllLanguages() {
        return $this->getAllObjects();
    }
    
    public function getUserLanguages($countriesIds){
        $countryids = "(" . join(',', $countriesIds) . ")";
        $sql = "SELECT * FROM languages
                INNER JOIN countries_languages ON countries_languages.language_id = languages.id 
                WHERE country_id in $countryids";
        $result = $this->conn->query($sql);
        if ($result === FALSE) {
            throw new Exception($this->conn->error);
        }
        $languages = [];
        while ( $row = $result->fetch_object('Language')){
            $languages[] = $row;
        }
        if (empty($languages)) {
            throw new Exception('failed');
        }
        return $languages;
                    
                
    }

}
