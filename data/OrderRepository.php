<?php
require_once 'BaseRepository.php';
require_once 'model/Customer.php';

class OrderRepository extends BaseRepository { 
    public function insertCustomer($customer) {
        $stmt = $this->conn->prepare("INSERT INTO customers(firstname,lastname,address,zipcode,city,country,email,phonenumber) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
        $bindresult = $stmt->bind_param("ssssssss", $fn, $ln, $a, $z, $c, $co, $e, $t);
        $fn = $customer->getFirstName();
        $ln = $customer->getLastName();
        $a = $customer->getAddress();
        $z = $customer->getZipCode();
        $c = $customer->getCity();
        $co = $customer->getCountry();
        $e = $customer->getEmail();
        $t = $customer->getPhoneNumber();
        
        $stmt->execute();
    }
    
} 