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

}
