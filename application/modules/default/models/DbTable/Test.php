<?php

class Model_DbTable_Test extends Zend_Db_Table_Abstract {

    //Var Declaration
    protected $_name = "countries";

    function test($name, $code) {

        $this->_db->insert($this->_name, array("name" => $name, "code" => $code));

    }
    
    function encodeing(){
        $this->_db->query("SET NAMES 'utf8'");
    }

}
?>
