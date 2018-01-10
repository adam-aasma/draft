<?php
namespace Walltwisters\data; 

use Walltwisters\interfacesrepo\ILanguageRepository;
use Walltwisters\model\Language;

require_once 'interfacesrepo/ILanguageRepository.php';
require_once 'BaseRepository.php';
require_once 'model/Language.php';

class LanguageRepository extends BaseRepository implements ILanguageRepository {
    
    public function __construct() {
        parent::__construct("languages", "Walltwisters\model\Language");
    }
    
    protected function getColumnNamesForInsert() {
        throw new Exception("Not implemented");
    }
    
    protected function getColumnValuesForBind($aggregate) {
        throw new Exception("Not implemented");
    }
    
    public function getAllLanguages() {
        return $this->getAll();
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
        while ( $row = $result->fetch_object('Walltwisters\model\Language')){
            $languages[] = $row;
        }
        if (empty($languages)) {
            throw new Exception('failed');
        }
        return $languages;
                    
                
    }

}
