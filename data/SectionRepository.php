<?php

require_once 'data/BaseRepository.php';
require_once 'model/Section.php';
class SectionRepository extends BaseRepository {
    public function __construct() {
        parent::__construct("sections", "Section");
    }
    
    protected function getColumnNamesForInsert() {
        throw new Exception("Not implemented");
    }
    
    protected function getColumnValuesForBind($aggregate) {
        throw new Exception("Not implemented");
    }
}
