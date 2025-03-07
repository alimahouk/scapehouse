<?php

class Logged_Model_DbTable_Note extends Zend_Db_Table_Abstract {

    protected $_name = "note";
    
    function createNote ($content, $userid ,$reciverid, $color) {
        
        $this->_db->insert($this->_name, array(
                "content" => $content,
                "userid" => $userid,
                "reciverid" => $reciverid,
                "color" => $color,
                "time" => gmdate("Y-m-d H:i:s", time())));

       echo $noteid = $this->_db->lastInsertId($this->_name);
       // $GLOBALS["jsonResponce"]["replyid"] = $replyid;
        
      //  $scapeTable = new Logged_Model_DbTable_Scape();
      //  $reciverid = $scapeTable->getUserid($scapeid);

        $notifTable = new Logged_Model_DbTable_Notif();
        $notifTable->createNotif($reciverid,"note", $noteid, "user", $reciverid);

    }

   function getNoteById($id) {
        $query = $this->_db->select()->from($this->_name)->join("users","note.userid = users.id",array("firstname","lastname","username"))->where("note.id=?",$id);
        return $this->_db->fetchRow($query);
    }
    
    function getNotesByReciverid($id,$limit){

        $query = $this->_db->select()->from($this->_name)
        ->join("users","note.userid = users.id",array("firstname","lastname","username"))->where("reciverid=?",$id)
        ->order("time DESC")->limit(15,$limit);
        return $this->_db->fetchAll($query);
    }
    
    function getAllNoteIdsByReciverid($id){
        $query = $this->_db->select()->from($this->_name,"id")->where("reciverid=?",$id);
        return $this->_db->fetchCol($query);
    }
    
    function deleteNote($id){
        
        $user = $this->_db->fetchRow($this->_db->select()->from($this->_name,array("userid","reciverid"))->where("id=?",$id));
        
        if($user["userid"] == $GLOBALS["session"]->id || $user["reciverid"] == $GLOBALS["session"]->id){
        $this->_db->delete($this->_name,"id=$id");
        $this->_db->delete("notif","`actionid`=$id AND `reltype` = 12");
        }
    }
 
}