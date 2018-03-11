<?php
namespace Walltwisters\repository;

use Walltwisters\repository\exceptions\DatabaseException;

abstract class BaseRepository {
    protected static $conn;
    private $tableName;
    private $objectName;
    
    function __construct($tableName = null, $objectName = null) {
        if(!isset($conn)){
            $servername = "127.0.0.1";
            $username = "adam";
            $password = "rocky";
            $dbname = "walltwisters"; 
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
    
    public function createOrUpdate($aggregate) {
        if ($this->exists($aggregate)) {
            $this->update($aggregate);
        } else {
            $this->create($aggregate);
        } 
    }
    
    public function delete($id) {
        $this->deleteForId('id', $id);
    }
    
    public function deleteForId($idName, $idValue) {
        $sql = "DELETE FROM $this->tableName WHERE $idName = ?";
        $stmt = self::$conn->prepare($sql);
        if ($stmt === false) {
            throw new \Exception("SQL syntax: " . $sql);
        }
        $stmt->bind_param('i', $idValue);
        $res = $stmt->execute();
        if (!$res) {
            throw new \Exception($stmt->error);
        }   
        
    }
    
    private function bindParams($stmt, $aggregate, $ids = []) {
        $bindTypes = '';
        $bindValues = [];
        if (!empty($aggregate)) {
            $bindTypesAndValues = empty($ids) ? 
                $this->getColumnValuesForBind($aggregate) :
                $this->getColumnValuesForBindUpdate($aggregate);
            $bindTypes = join('', array_map(function($bv) { return $bv[0]; }, $bindTypesAndValues));
            $bindValues = array_map(function($bv) {return $bv[1];}, $bindTypesAndValues);
        }
        if (!empty($ids)) {
            foreach($ids as $id) {
                $bindTypes .= 'i';
                $bindValues[] = $id;
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
