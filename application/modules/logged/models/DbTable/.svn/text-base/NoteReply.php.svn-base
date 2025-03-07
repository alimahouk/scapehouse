<?php

class Logged_Model_DbTable_NoteReply extends Zend_Db_Table_Abstract {

    protected $_name = "note_reply";
    
    function createNoteReply ($content, $userid, $noteid) {
        
        $this->_db->insert($this->_name, array(
                "content" => $content,
                "userid" => $userid,
                "noteid" => $noteid,
                "time" => gmdate("Y-m-d H:i:s", time())));

       echo $noteReplyId = $this->_db->lastInsertId($this->_name);

     //   $notifTable = new Logged_Model_DbTable_Notif();
     //   $notifTable->createNotif($noteReplyId,"noteReply", $noteid, "note", $reciverid);

    }
    
    function getNoteRepliesOnNote($noteid){
        
       $query = $this->_db->select()->from($this->_name)
        ->join("note","note.id = note_reply.noteid",array("color"))
        ->join("users","users.id=note_reply.userid",array("firstname","lastname","username"))->where("note_reply.noteid=?",$noteid);
        
       return $this->_db->fetchAll($query);
    }
    
    function getNoteReplyById($id){
        
       $query = $this->_db->select()->from($this->_name)
        ->join("note","note.id = note_reply.noteid",array("color"))
        ->join("users","users.id=note_reply.userid",array("firstname","lastname","username"))->where("note_reply.id=?",$id);
        
       return $this->_db->fetchRow($query);
    }
}

?>