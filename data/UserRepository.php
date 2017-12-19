<?php

require_once 'BaseRepository.php';
require_once 'model/User.php';
require_once 'model/Country.php';
require_once 'model/Privileges.php';

class UserRepository extends BaseRepository { 
    public function LoginUser($userName, $password) {
         $sql = "SELECT * FROM users WHERE username = '$userName'";
        $result = $this->conn->query($sql);
        if ($result === FALSE) {
            throw new DatabaseException($this->conn->error);
        }
        $row = $result->fetch_assoc();
        if ($row){
            $pwd = $row['password'];
            $salt = $row['passwordsalt'];
            $str = $salt . $password;
            $hash = md5($str);
            if (strtoupper($hash) == strtoupper($pwd)) {
                $id = $row['id'];
                $name = $row['name'];
                $lastname = $row['lastname'];
                $privileges = $row['privileges'];
                $user = new User($id, $name, $lastname, $userName, $privileges, []);
                $countries = $this->getCountriesForUser($user);
                $user->countries = $countries;
                return $user;
            }
        }
        return false;
    }
    
    private function PasswordCryp($password) {
        $salt = uniqid();
        $strn = $salt . $password;
        $hash = md5($strn);
       
        return [$hash, $salt];
    }
    
  
    
    public function AddUser($user) {
        $name = $user->name;
        $lastname = $user->lastname;
        $username = $user->username;
        $privileges = $user ->privileges;
        $country = $user -> country;
        list($password, $salt) =$this->PasswordCryp($user->password);
        $sql = ("INSERT INTO users(name,lastname,username,password, passwordsalt, privileges) VALUES(?, ?, ?, ?, ?, ?)");
        $stmt = $this->conn->prepare($sql);
        $bindresult = $stmt->bind_param("sssssi", $name, $lastname, $username, $password, $salt, $privileges);
        if (!$bindresult ) {
            throw Exception('fail to bind');
        }
        $res = $stmt->execute();
        if ($res) {
            $lastIdRes = $this->conn->query("SELECT LAST_INSERT_ID()");         
            $row = $lastIdRes->fetch_row();                                       
            $userid = $row[0];
            $this->AddUserCountry($userid, $country->id);
            $user->id = $userid;
            return $user;
        }
        throw new Exception($stmt->error);
    }
    
    private function AddUserCountry($userid, $countryid) {
        $sql = ("INSERT INTO user_country(user_id,country_id) VALUES(?, ?)");
        $stmt = $this->conn->prepare($sql);
        $bindresult = $stmt->bind_param("ii", $userid, $countryid);
        if (!$bindresult ) {
            throw Exception('fail to bind');
        }
        return $stmt->execute();
        
    }
    private function getCountriesForUser($user) {
        $user_id = $user->id;
        $sql = ("SELECT countries.* FROM countries, user_country WHERE user_country.user_id = $user_id
                AND countries.id = user_country.country_id;");
        $result = $this->conn->query($sql);
        if ($result === FALSE) {
            throw new Exception($this->conn->error);
        }
        $countries = [];
        while ( $row = $result->fetch_object('Country')){
            $countries[] = $row;
        }
        if (empty($countries)) {
            throw new Exception('failed');
        }
        return $countries;
                
    }
    
    public function GetPrivileges() {
            $sql = ("SELECT id, privileges, description FROM privileges");
            $result = $this->conn->query($sql);
            if ($result === FALSE) {
                throw new Exception($this->conn->error);
            }
            $priv = [];
            while ($row = $result->fetch_object('Privileges')){
                $priv[] = $row;
            }
            if (empty($priv)) {
                throw new Exception('failed');
            }
            return $priv;
    }
    
     public function GetCountries() {
            $sql = ("SELECT id, country FROM countries");
            $result = $this->conn->query($sql);
            if ($result === FALSE) {
                throw new Exception($this->conn->error);
            }
            $countries = [];
            while ($row = $result->fetch_object('Country')){
                $countries[] = $row;
            }
            if (empty($countries)) {
                throw new Exception('failed');
            }
            return $countries;
    }
    
  /*  public function GetUserPrivileges($username) {
        $sql = ("SELECT privileges FROM users WHERE username='$username->username'");
        $result = $this->conn->query($sql);
        if ($result === FALSE) {
            throw new Exception($this->conn->error);
        }
        $row = $result->fetch_assoc();
        $userprivileges = $row['privileges'];
        return $userprivileges;
            
    }*/
} 