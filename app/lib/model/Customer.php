<?php
namespace Walltwisters\lib\model; 

class Customer {
    private $firstname;
    private $lastname;
    private $address;
    private $zipcode;
    private $city;
    private $country;
    private $email;
    private $phonenumber;
    
    function __construct($firstname,
        $lastname,
        $address,
        $zipcode,
        $city,
        $country,
        $email,
        $phonenumber){
        
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->address = $address;
        $this->zipcode = $zipcode;
        $this->city = $city;
        $this->country = $country;
        $this->email = $email;
        $this->phonenumber = $phonenumber;
   }
    
    public function getFirstName(){
        return $this->firstname;
    }
    public function getLastName(){
        return $this->lastname;
    }
    public function getAddress(){
        return $this->address;
    }
    public function getZipCode(){
        return $this->zipcode;
    }
    public function getCity(){
        return $this->city;
    }
    public function getCountry(){
        return $this->country;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getPhoneNumber(){
        return $this->phonenumber;
    }
    
    
        
        
        
        
        
}
