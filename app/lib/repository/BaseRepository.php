<?php
namespace Walltwisters\lib\repository;

use Walltwisters\lib\repository\exceptions\DatabaseException;

abstract class BaseRepository {
    protected static $conn;
    private $tableName;
    private $objectName;
    
    function __construct($tableName = null, $objectName = null) {
        if(!isset($conn)){
            $mysqlSettings = $GLOBALS['config']['settings']['mysql'];
            $servername = $mysqlSettings['servername'];
            $username = $mysqlSettings['username'];
            $password = $mysqlSettings['password'];
            $dbname = $mysqlSettings['dbname'];
            self::$conn = new \mysqli($servername, $username, $password, $dbname);
            if (self::$conn->connect_error) {
                throw new DatabaseException(self::$conn->connect_error);
            } 
        }
        $this->tableName = $tableName;
        $this->objectName = $objectName;
    }
     
    abstract protected function getColumnNamesForInsert();
    abstract protected function getColumnValuesForBind($aggregate);
    protected function getColumnNamesForUpdate() {
        return $this->getColumnNamesForInsert();
    }
    protected function getColumnValuesForBindUpdate($aggregate) {
        return $this->getColumnValuesForBind($aggregate);
    }
    
    public function getAll() {
        $sql = ("SELECT * FROM $this->tableName");         
        $result = self::$conn->query($sql);                                                                        
        if ($result === FALSE) {
            throw new Exception(self::$conn->error);
        }
        $collection = [];

        while ($row = $result->fetch_object($this->objectName)){
            $collection[] = $row;
        }
        return $collection;                                       
    }
    
    public function ifRowExistsReturnElseCreate($aggregate) {
       list($conditions, $idValues) = $this->getIds($aggregate);
       $sql = "SELECT * FROM $this->tableName WHERE " . join(" AND ", $conditions);
       $stmt = self::$conn->prepare($sql);
       if ($stmt === false) {
            throw new \Exception("SQL syntax: " . $sql);
        }
        $this->bindParams($stmt, null, $idValues);
        $res = $stmt->execute();
        if ($res) {
            $collection =  $this->createObjArray($stmt);
        }
        
        if (!empty($collection)){
            return $collection;
        } else {
            return $this->create($aggregate, true);
        }
        
        
        
    }
    
    public function exists($aggregate) {
        list($conditions, $idValues) = $this->getIds($aggregate);
        $sql = "SELECT COUNT(*) FROM $this->tableName WHERE " . join(" AND ", $conditions);
        $stmt = self::$conn->prepare($sql);
        if ($stmt === false) {
            throw new \Exception("SQL syntax: " . $sql);
        }
        $this->bindParams($stmt, null, $idValues);
        $res = $stmt->execute();
        if (!$res) {
            throw new \Exception($stmt->error);
        }  
        $stmt->bind_result($count);
        $okfetch = $stmt->fetch();
        return $okfetch && $count > 0;
    }
    
    /*
     * updated thes function to accept array of condition
     * if problem ariseset a method in the model class
     */
    
    public function deleteForId($aggregate) {
        list($conditions, $idValues) = $this->getIds($aggregate);
        $sql = "DELETE FROM $this->tableName WHERE " . join(" AND ", $conditions);
        $stmt = self::$conn->prepare($sql);
        if ($stmt === false) {
            throw new \Exception("SQL syntax: " . $sql);
        }
        $this->bindParams($stmt, null, $idValues);
        $res = $stmt->execute();
        if (!$res) {
            throw new \Exception($stmt->error);
        }
        return $stmt->affected_rows;
         
        
    }
    
    public function update($aggregate) {
        $colNames = $this->getColumnNamesForUpdate();
        $colList = '';
        foreach($colNames as $colName) {
            if (!empty($colList)) {
                $colList .= ', ';
            }
            $colList .= $colName . ' = ?';
        }
        list($conditions, $idValues) = $this->getIds($aggregate);
        $sql = "UPDATE $this->tableName SET $colList WHERE " . join(" AND ", $conditions);
        $stmt = self::$conn->prepare($sql);
        if ($stmt === false) {
            throw new \Exception("SQL syntax: " . $sql);
        }
        $this->bindParams($stmt, $aggregate, $idValues);
        $res = $stmt->execute();
        if (!$res) {
            throw new \Exception($stmt->error);
        }   
        return $aggregate;
    }
    
    public function create($aggregate, bool $getId = false) {
        $colNames = $this->getColumnNamesForInsert();
        $colList = join(',', $colNames);
        $colVals = join(',', array_map(function ($n) { return '?'; }, $colNames));
        $sql = "INSERT INTO $this->tableName ($colList) VALUES($colVals)";
        $stmt = self::$conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("SQL syntax: " . $sql);
        }
        $this->bindParams($stmt, $aggregate);
        $res = $stmt->execute();
        if (!$res) {
            throw new \Exception($stmt->error);
        }   

        if ($getId) {
            $lastIdRes = self::$conn->query("SELECT LAST_INSERT_ID()");         
            $row = $lastIdRes->fetch_row();                                       
            $lastId = $row[0];
            $aggregate->id = $lastId;
        }
        
        return $aggregate;
    }
    
    public function createOrUpdate($aggregate, bool $getId = false) {
        if ($this->exists($aggregate)) {
            return $this->update($aggregate);
        } else {
            return $this->create($aggregate, $getId);
        } 
    }
    
    
    
    private function bindParams($stmt, $aggregate, $values = []) {
        $bindTypes = '';
        $bindValues = [];
        if (!empty($aggregate)) {
            $bindTypesAndValues = empty($values) ? 
                $this->getColumnValuesForBind($aggregate) :
                $this->getColumnValuesForBindUpdate($aggregate);
            $bindTypes = join('', array_map(function($bv) { return $bv[0]; }, $bindTypesAndValues));
            $bindValues = array_map(function($bv) {return $bv[1];}, $bindTypesAndValues);
        }
        if (!empty($values)) {
            foreach($values as $value) {
                $bindTypes .= is_numeric($value) ? 'i' : 's';
                $bindValues[] = $value;
            }
        }
        $bindp = [];
        $bindp[] = &$bindTypes;
        for ($i = 0; $i < count($bindValues); $i++) {
            $bindp[] = &$bindValues[$i];
        }

        call_user_func_array(array($stmt, "bind_param"), $bindp);
    }

    private function getIds($aggregate) {
        $conditions = [];
        $idValues = [];
        if (method_exists($aggregate, "getIdArray")) {
            $ids = $aggregate->getIdArray();
            foreach($ids as $name => $value) {
                $conditions[] = $name . " = ?";
                $idValues[] = $value;
            }
        } else {
            $conditions[] = "id = ?";
            $idValues[] = $aggregate->id;
        }
        
        return [$conditions, $idValues];
    }

    protected function createStatementForInClause($query, $colName, $inValues, $typeLetter) {
        $qs = array_map(function() { return '?'; }, $inValues);
        $inclause = implode(',', $qs);
        $bs = array_map(function() use($typeLetter) { return $typeLetter; }, $inValues);
        $stmt = self::$conn->prepare($query . " WHERE $colName IN ($inclause)");         
        $bindTypes = implode('', $bs);
        $bindp = [];
        $bindp[] = &$bindTypes;
        for ($i = 0; $i < count($inValues); $i++) {
            $bindp[] = &$inValues[$i];
        }
        call_user_func_array(array($stmt, "bind_param"), $bindp);
        
        return $stmt;
    }
    
    
    
    
}
