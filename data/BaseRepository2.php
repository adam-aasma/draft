<?php


abstract class BaseRepository2 {
    
    protected static function conn() {
       $servername = "127.0.0.1";
        $username = "adam";
        $password = "Oskar.#4837";
        $dbname = "WT_Test"; 
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($this->conn->connect_error) {
            //throw new DatabaseException($this->conn->connect_error);
            throw new Exception($this->conn->connect_error);
        }
        return $conn;
    }
    
    abstract protected function select();
    abstract protected function insert();
    
    protected function selectfrom($column, $table){
        $sql = "(SELECT '$column'FROM '$table";
        
    }
        
    
}
