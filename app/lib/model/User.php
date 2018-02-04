<?php
namespace Walltwisters\model;



class User {
    private $id;
    private $name;
    private $lastname;
    private $username;
    private $password;
    private $privileges;
    private $countries;
    
    function __construct($id, $name, 
        $lastname, $username,
        $privileges, $countries){
        
        $this->id = $id;
        $this->name = $name;
        $this->lastname = $lastname;
        $this->username = $username;
        $this->privileges = $privileges;
        $this->countries = $countries;
    }
    
    public function __get($name) {
        return $this->$name;
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }
    
    public function getUserName() {
        return $this->name;
    }
    
    public function getUsersUsername(){
        return $this->username;
    }
    
    public function getLastName(){
        return $this->lastname;
    }
    
    public function getPassword(){
        return $this->password;
    }
    
    public function getPrivileges() {
        return $this->privileges;
    }
}