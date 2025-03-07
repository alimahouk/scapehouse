<?php

class Logged_Model_DbTable_Profile extends Zend_Db_Table_Abstract {

    protected $_name = "profile";


    function save($userid,$data,$type) {
    
    $this->_db->delete($this->_name,"`userid`=$userid AND `type`=$type");
    
    if($data != NULL || $data != ""){
      $this->_db->insert($this->_name, array("userid" => $userid,"data" => $data,"type" => $type));
    }
    
    }
    
    function getProfileInfo($userid){
        
       $data = $this->_db->select()->from($this->_name)->join("type_profiledata","`type_profiledata`.`id` = `profile`.`type`","name")->where("userid=?",$userid);
       return $this->_db->fetchAll($data);
    }
    
    function getProfileInfoByName($userid,$infoNames){
        
        if(is_array($infoNames)){
            $infoNames = implode(",",$infoNames);
        }else{
            return false;
        }
        
        $data = $this->_db->select()->from($this->_name)->join("type_profiledata","`type_profiledata`.`id` = `profile`.`type`","name")
        ->where("userid=?",$userid)->where("name IN ($infoNames)");
       return $this->_db->fetchAll($data);
       
    }
}
?>
