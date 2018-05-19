<?php
namespace Walltwisters\lib\model; 

class Printer {
    private $id;
    private $companyName;
    private $email;
    private $phoneNumber;
    private $contactPerson;
    private $countryId;
    private $addedByUser;
    
    public function __get($name){
        return $this->$name;
    }
    
    public function __set($name, $value){
        $this->$name = $value;
    }
    
    public static function create($companyName, $email, $phoneNumber, $contactPerson, $countryId, $addedByUser, $id = null){
        $obj = new Printer();
        $obj->companyName = $companyName;
        $obj->email = $email;
        $obj->phoneNumber = $phoneNumber;
        $obj->contactPerson = $contactPerson;
        $obj->countryId = $countryId;
        $obj->addedByUser = $addedByUser;
        $obj->id = $id;
        return $obj;
    }
}

