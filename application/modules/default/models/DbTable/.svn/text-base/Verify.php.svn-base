<?php

class Model_DbTable_Verify extends Zend_Db_Table_Abstract {

    protected $_name = "verify";


    function save ($userid,$hash) {

        $create = $this->_db->insert($this->_name, array(
                "userid" => $userid,
                "hash" => $hash,
        ));

    }

    function isNotVerified($userid) {
        $verified = $this->_db->select()->from($this->_name,"id")->join("users", "`users`.`id`=`verify`.`userid`", array("email","time"))->where("userid=?",$userid);
        $result = $this->_db->fetchRow($verified);
        return $result;
    }

    function verify($hash) {
        
        $verify = $this->_db->select()->from($this->_name,"id")
                ->join("users", "`users`.`id`=`verify`.userid", array("username","password"))
                ->where("hash=?",$hash);   
        
        $result = $this->_db->fetchRow($verify);
        
        if($result){
             $this->_db->delete($this->_name, $this->_db->quoteInto("hash=?",$hash));
             return $result;
            }
        
    }


}

