<?php

class Logged_Model_DbTable_Scapebox extends Zend_Db_Table_Abstract {

    protected $_name = "scapebox_receive";
    
    function createEntry($scapeid,$receiverid,$senderid,$private=NULL){
         
         if($private = "true"){
            $private = 1;
         }elseif($private = "false"){
            $private = 0;
         }
         
         $this->_db->insert($this->_name,array(
            "receiverid" => $receiverid,
            "senderid" => $senderid,
            "scapeid" => $scapeid,
            "private" => $private,
            "imp" => 0
        ));
    }

    function getOthersScapes($userid) {
        
    $query = $this->_db->select()
        ->from($this->_name,array("scapeid as id","private","imp",
                                  '(SELECT CONCAT("[",GROUP_CONCAT(CONCAT(`receiverid`)),"]") FROM scapebox_receive WHERE scapebox_receive.scapeid = scape.id) AS receiverids',
                                  "(SELECT COUNT(*) FROM `reply` WHERE `reply`.`scapeid` = scapebox_receive.scapeid) AS replyCount"))
        ->join("scape","scapebox_receive.scapeid = scape.id", array("scape.userid as userid","content","title","time"))
        ->join("users","users.id = scape.userid",array("firstname","lastname","username"))
        ->where("scapebox_receive.receiverid=?",$userid)
        ->order("time DESC");
        
    return $this->_db->fetchAll($query);

    }
    
    function getMyScapes($userid) {
        
    $query = $this->_db->select()
        ->from($this->_name,array("scapeid as id","private","imp",
                                  '(SELECT CONCAT("[",GROUP_CONCAT(CONCAT(`receiverid`)),"]") FROM scapebox_receive WHERE scapebox_receive.scapeid = scape.id) AS receiverids',
                                  "(SELECT COUNT(*) FROM `reply` WHERE `reply`.`scapeid` = scapebox_receive.scapeid) AS replyCount"))
        ->join("scape","scapebox_receive.scapeid = scape.id", array("scape.userid as userid","content","title","time"))
        ->join("users","users.id = scape.userid",array("firstname","lastname","username"))
        ->where("scapebox_receive.senderid=?",$userid)
        ->order("time DESC")
        ->group("scapeid");
        
    return $this->_db->fetchAll($query);

    }
    
    function newScapeCount(){
        $query = $this->_db->select()
        ->from($this->_name,"COUNT(id)")
        ->where("receiverid=?",$GLOBALS["session"]->id)
        ->where("imp=?",0);
        
      return $this->_db->fetchOne($query);  
    }
    
    function addImp($scapid){
       $this->_db->update($this->_name,array("imp" => 1),"`receiverid`={$GLOBALS["session"]->id} AND `scapeid`=$scapid");    
    }
    
    function delete($userid,$scapeid){
        $this->_db->delete($this->_name,"receiverid=$userid AND scapeid=$scapeid");
    }

}