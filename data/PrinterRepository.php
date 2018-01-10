<?php
namespace Walltwisters\data; 

require_once 'model/Printer.php';

class PrinterRepository extends BaseRepository{
   
    public function __construct() {
        parent::__construct("printers", "Walltwisters\model\Printer");
    }
   
    protected function getColumnNamesForInsert() {
        return ['company_name', 'email', 'telephone', 'contact_name', 'country_id', 'added_by_user'];
    }
    
    protected function getColumnValuesForBind($printer) {
        $company_name = $printer->companyName;
        $email = $printer->email;
        $telephone = $printer->phoneNumber;
        $contact_name = $printer->contactPerson;
        $country_id = $printer->countryId;
        $added_by_user = $printer->addedByUser;

        return [['s', &$company_name], ['s', &$email], ['i', &$telephone], ['s', &$contact_name], ['i', &$country_id], ['i', &$added_by_user]];
    }
}
