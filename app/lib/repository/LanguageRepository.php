<?php

namespace Walltwisters\repository;

use Walltwisters\interfacesrepo\ILanguageRepository;

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

    public function getUserLanguages($countryIds) {
        $query = "SELECT id, language FROM languages
                INNER JOIN countries_languages ON countries_languages.language_id = languages.id";
        $stmt = $this->createStatementForInClause($query, 'country_id', $countryIds, 'i');
        $res = $stmt->execute();
        $languages = [];
        if ($res) {
            $stmt->bind_result($id, $language);
            while ($stmt->fetch()) {
                $languages[] = \Walltwisters\model\Language::create($id, $language);
            }
        }
        if (empty($languages)) {
            throw new Exception('failed');
        }
        return $languages;
    }

    public function getCountryLanguages($countryIds) {
        $query = "SELECT DISTINCT country_id, language_id, language FROM languages
                INNER JOIN countries_languages ON countries_languages.language_id = languages.id";
        $stmt = $this->createStatementForInClause($query, 'country_id', $countryIds, 'i');
        $res = $stmt->execute();
        $countryLanguages = [];
        if ($res) {
            $stmt->bind_result($country_id, $language_id, $language);
            while ($stmt->fetch()) {
                $countryLanguages[$country_id][] = ['languageId' => $language_id, 'languageName' => $language];
            }
        }
        return $countryLanguages;
    }

}
