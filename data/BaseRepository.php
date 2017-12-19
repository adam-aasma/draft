<?php
require_once 'Exceptions.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseRepository
 *
 * @author adam
 */
class BaseRepository {
    //put your code here
    protected $conn;
    
    /** Called automatically when object created through new ImageRepository() */
    function __construct() {
        $servername = "127.0.0.1";
        $username = "root";
        $password = "Oskar.#4837";
        $dbname = "WT_Test"; 
        $this->conn = new mysqli($servername, $username, $password, $dbname);
        if ($this->conn->connect_error) {
            //throw new DatabaseException($this->conn->connect_error);
            throw new Exception($this->conn->connect_error);
        } 
    }
    
    function __destruct() {
        $this->conn->close();
    }
    
}
