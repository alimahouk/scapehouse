<?php

class Logged_Model_DbTable_Countries extends Zend_Db_Table_Abstract {

    protected $_name = "countries";
    
    function inRecord($code){
        $query = $this->_db->select()->from($this->_name)->where("code=?",$code);
        return $this->_db->fetchOne($query);
    }
    
    function getCountryByCode($code){
        $query = $this->_db->select()->from($this->_name,"name")->where("code=?",$code);
        return $this->_db->fetchOne($query);
    }
    
    function getAll(){
        $query = $this->_db->select()->from($this->_name);
        return $this->_db->fetchAll($query);     
    }

}