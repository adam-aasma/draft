<?php

require_once 'data/BaseRepository.php';
require_once 'model/Country.php';
require_once 'interfacesrepo/ICountryRepository.php';

class CountryRepository extends BaseRepository implements ICountryRepository {
    
    public function __construct() {
        parent::__construct("countries", "Country");
    }
    
    public function getAllCountries() {
        return $this->getAllObjects();
    }

}
 