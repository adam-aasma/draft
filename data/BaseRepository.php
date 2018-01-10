<?php
namespace Walltwisters\data;

require_once 'Exceptions.php';

abstract class BaseRepository {
    protected $conn;
    private $tableName;
    private $objectName;
    
    function __construct($tableName = null, $objectName = null) {
        $servername = "127.0.0.1";
        $username = "root";
        $password = "Oskar.#4837";
        $dbname = "WT_Test"; 
        $this->conn = new \mysqli($servername, $username, $password, $dbname);
        if ($this->conn->connect_error) {
            throw new Exception($this->conn->connect_error);
        } 
        
        $this->tableName = $tableName;
        $this->objectName = $objectName;
    }
    
    function __destruct() {
        $this->conn->close();
    }
    
    abstract protected function getColumnNamesForInsert();
    abstract protected function getColumnValuesForBind($aggregate);
    
    public function getAll() {
        $sql = ("SELECT * FROM $this->tableName");         
        $result = $this->conn->query($sql);                                                                        
        if ($result === FALSE) {
            throw new Exception($this->conn->error);
        }
        $collection = [];

        while ($row = $result->fetch_object($this->objectName)){
            $collection[] = $row;
        }
        return $collection;                                       
    }
    
    public function save($aggregate, bool $getId = false) {
        $colNames = $this->getColumnNamesForInsert();
        $colList = join(',', $colNames);
        $colVals = join(',', array_map(function ($n) { return '?'; }, $colNames));
        $bindTypesAndValues = $this->getColumnValuesForBind($aggregate);
        $bindTypes = join('', array_map(function($bv) { return $bv[0]; }, $bindTypesAndValues));
        //function &getVal(&$bv) {
        //    return $bv[1];
        //}

        $bindValues = array_map(function($bv) {return $bv[1];}, $bindTypesAndValues);
        $bindp = [];
        $bindp[] = &$bindTypes;
        for ($i = 0; $i < count($bindValues); $i++) {
            $bindp[] = &$bindValues[$i];
        }

        $sql = "INSERT INTO $this->tableName ($colList) VALUES($colVals)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("SQL syntax: " . $sql);
        }
        call_user_func_array(array($stmt, "bind_param"), $bindp);
        $res = $stmt->execute();
        if (!$res) {
            throw new \Exception($stmt->error);
        }   

        if ($getId) {
            $lastIdRes = $this->conn->query("SELECT LAST_INSERT_ID()");         
            $row = $lastIdRes->fetch_row();                                       
            $lastId = $row[0];
            $aggregate->id = $lastId;
        }
        
        return $aggregate;
    }

}
