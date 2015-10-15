<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $YII_all_tables;
$YII_all_tables = array();


class YiiTables{
    var $_primary = 'id';
    var $_tablename = "{{menus}}";
    var $_message = "";
    var $_db = null;   
    
    
    function __construct($tbl_name, $primary = "id", $db) {
        $this->_tablename = $tbl_name;
        $this->_primary = $primary;
        
        $this->_db = $db;        
        if($this->_db == null) $this->_db = Yii::app()->db;
        
        $query = "SHOW COLUMNS IN $this->_tablename";
        $query = "DESCRIBE $this->_tablename";
        $query_command = $this->_db->createCommand($query);
        $fields = $query_command->queryColumn();
        foreach ($fields as $field){
            $this->$field = "";
        }
        $this->_message = '';
    }
    
    static function & getInstance($tbl_name, $primary = "id", $db = null) {
        global $YII_all_tables;
        
        $key_obj = md5($tbl_name . $primary);
        if(isset($YII_all_tables[$key_obj])) return $YII_all_tables[$key_obj];
        
        $obj_table = new YiiTables($tbl_name, $primary, $db);
        $YII_all_tables[$key_obj] = $obj_table;

        return $YII_all_tables[$key_obj];
    }    
    
    function load($id, $field = "*"){ 
        if ($id === 0 || $id == "") {
            return $this;
        }
        $id = trim($id);
        $query = "SELECT $field FROM $this->_tablename WHERE $this->_primary = :fieldvalue ";
        $query_command = $this->_db->createCommand($query);
        $query_command->bindParam(':fieldvalue', $id);

        $item = $query_command->queryRow();
        if($item == false){
            $this->_message = "Something error to load row width value: $id";
            return $this;
        }
        foreach($item as $field => $field_value){
            $this->$field = $field_value;
        }
        return $this;
    }
    
    function loads($field = "*", $conditions = null, $orderBy = "", $limit = 10, $start = 0){
        if($orderBy == "" OR $orderBy == null){
            $pname = $this->_primary;
            if(isset($this->$pname))
                $orderBy = " $this->_primary DESC ";
        }
        $command = $this->_db->createCommand()->select($field)
                ->from($this->_tablename);
        
        if($conditions != null) $command->where($conditions);
        if($orderBy != null AND $orderBy != "") $command->order($orderBy);
        if($limit != null)$command->limit($limit, $start);
        
        $results = $command->queryAll();
        return $results;
    }
    
    function loadColumn($field = "id", $conditions = null, $orderBy = "", $limit = 10, $start = 0){
        if($orderBy == "" OR $orderBy == null){
            $pname = $this->_primary;
            if(isset($this->$pname))
                $orderBy = " $this->_primary DESC ";
        }
        $command = $this->_db->createCommand()->select($field)
                ->from($this->_tablename);
       
        if($conditions != null) $command->where($conditions);
        if($orderBy != null AND $orderBy != "") $command->order($orderBy);
        if($limit != null)$command->limit($limit, $start);
        
        $results = $command->queryColumn();
    
        return $results;
    }
    
    function loadRow($field = "id", $conditions = null, $orderBy = "", $limit = 10, $start = 0){
        if($orderBy == "" OR $orderBy == null){
            $pname = $this->_primary;
            if(isset($this->$pname))
                $orderBy = " $this->_primary DESC ";
        }
        $command = $this->_db->createCommand()->select($field)
                ->from($this->_tablename);

        if($conditions != null) $command->where($conditions);
        if($orderBy != null AND $orderBy != "") $command->order($orderBy);
        if($limit != null)$command->limit($limit, $start);
        
        $items = $command->queryRow();
        foreach($items as $field => $field_value){
            $this->$field = $field_value;
        }
        return $items;
    }
    
    function getTotal($conditions = null){
        $command = $this->_db->createCommand()->select("count(*)")
                ->from($this->_tablename);
        if($conditions != null) $command->where($conditions);
        $result = $command->queryScalar();
        return $result;
    }
    
    function bind($fromArray){
        foreach ($this as $field_name => $field_value) {
            if(strpos($field_name, "_") !== false) continue;
            if (isset($fromArray[$field_name]) and $fromArray[$field_name] != "" and $fromArray[$field_name] != null)
                $this->$field_name = $fromArray[$field_name];
        }
        return $fromArray;
    }
    
    function store(){
        $insterted = array();
        foreach ($this as $field_name => $field_value) {
            if(strpos($field_name, "_") !== false) continue;
            $insterted[] = "`$field_name`=:$field_name";
        }
        $insterted = implode(",", $insterted); 
       
        $id = $this->{$this->_primary};
        
        $query = "";
        if ($id != 0) {
            if (isset($this->mdate))
                $this->mdate = date("Y-m-d H:i:s");
            $query = "UPDATE $this->_tablename SET " . $insterted . " WHERE $this->_primary = $id";
        } else {
            if (isset($this->cdate))
                $this->cdate = date("Y-m-d H:i:s");
            if (isset($this->mdate))
                $this->mdate = date("Y-m-d H:i:s");
            $query = "INSERT INTO $this->_tablename SET " . $insterted;
        }
        $query_command = $this->_db->createCommand($query);
        foreach ($this as $field_name => $field_value) {
            if(strpos($field_name, "_") !== false) continue;
            $query_command->bindParam(':' . $field_name, $this->$field_name);
        } 
 
        $query_command->execute();
        if ($id == 0)
            $id = $this->_db->lastInsertID;
         
        $this->{$this->_primary} = $id;
        return $this;
    }
    
    function remove($id = null, $condition = "")
    {
        if($id == null OR $id == 0 or $id == ""){
            $id = $this->{$this->_primary};
        }
        
        if($id != null and $id != 0 and $id != ""){
            $query = "DELETE FROM $this->_tablename WHERE  $this->_primary = $id ";
            $query_command = $this->_db->createCommand($query);
            $query_command->execute();
            return true;
        }else if($condition != ""){
            $query = "DELETE FROM $this->_tablename WHERE $condition ";
            $query_command = $this->_db->createCommand($query);
            $query_command->execute();
            return true;
        }
        return false;
    }
}
