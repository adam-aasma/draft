<?php
namespace Walltwisters\lib\repository; 

use Walltwisters\lib\interfacesrepo\ICountryRepository;

class CountryRepository extends BaseRepository implements ICountryRepository {
    
    public function __construct() {
        parent::__construct("countries", "Walltwisters\lib\model\Country");
    }
    
    protected function getColumnNamesForInsert() {
        throw new Exception("Not implemented");
    }
    
    protected function getColumnValuesForBind($aggregate) {
        throw new Exception("Not implemented");
    }
    
    /**
     * 
     * @return type
     */
    public function getAllCountries() {
        return $this->getAll();
    }

}
 