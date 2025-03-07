<?php

class Logged_Model_DbTable_Comment extends Zend_Db_Table_Abstract {

    protected $_name = "comment";


    function createComment ($content, $elmid, $idType) {

        switch($idType) {
            case "scape":
                $subjType = 2;
                break;
            case "reply":
                $subjType = 3;
                break;
            case "photo":
                $subjType = 4;
                break;
        }

        $session = Model_StaticFunctions::sessionContent();
        $userid = $session->id;

        if(isset($content,$elmid,$idType)) {

            $this->_db->insert($this->_name, array(
                    "content" => $content ,
                    "userid" => $userid,
                    "subjid" => $elmid,
                    "subjtype" => $subjType,
                    "time" => gmdate("Y-m-d H:i:s", time())));

            $commentid = $this->_db->lastInsertId($this->_name);
            $GLOBALS["jsonResponce"]["commentid"] = $commentid;
            
            $reciverid = $this->_db->select()->from($idType,"userid")->where("id=?",$elmid)->limit("1");
            $reciverid = $this->_db->fetchOne($reciverid);
            
        $users = $this->getUsersForNotifier($elmid,$subjType);
        $users = array_unique($users);
        
        $notifTable = new Logged_Model_DbTable_Notif();
        
       if($users) {
        foreach($users as $userid){
            if($reciverid != $userid){ // This should prevent the owner of the elm getting notified twice
            $notifTable->createNotif($userid,"comment", $commentid, $idType, $elmid);
            }
        }
       } 
            $notifTable = new Logged_Model_DbTable_Notif();
            $notifTable->createNotif($reciverid,"comment", $commentid, $idType, $elmid);

        }

    }

    function getUsersForNotifier($elmid,$idType) {
        
        $query = $this->_db->select()->from($this->_name,"userid")->where("subjid=?",$elmid)->where("subjtype=?",$idType)->order("time DESC")->limit("4");
        $results = $this->_db->fetchCol($query);
        return $results;

    }
    
    function getAllCommentsOnElm($elmid,$idType) {

        switch($idType) {
            case "scape":
                $subjType = 2;
                break;
            case "reply":
                $subjType = 3;
                break;
            case "photo":
                $subjType = 4;
                break;
        }

        if (isset($elmid,$idType)) {
            $idType = $idType."id";
            $comments = $this->_db->select()->from($this->_name)->join("users", "`users`.`id`=`comment`.`userid`", array("CONCAT(`users`.`firstname`,' ',`users`.`lastname`) AS fullname","username"))
                    ->where("subjid=?", $elmid)->where("subjtype=?", $subjType)->order("time");
            $result = $this->getAdapter()->fetchAll($comments);
            return $result;
        } else {
            return false;
        }
    }


    function postedComment($commentid) {

        $comment = $this->_db->select()->from($this->_name)->join("users", "`users`.`id`=`comment`.`userid`", array("CONCAT(`users`.`firstname`,' ',`users`.`lastname`) AS fullname","username"))->where("`comment`.`id`=?",$commentid)->limit("1");
        $result = $this->getAdapter()->fetchRow($comment);
        return $result;

    }

    function editComment($content,$commentid) {

        $session = Model_StaticFunctions::sessionContent();

        $this->_db->update($this->_name,array("content" => $content),"`id`={$commentid} AND `userid`={$session->id}");
        $comment = $this->_db->select()->from($this->_name)->where("id=?",$commentid);
        $result = $this->getAdapter()->fetchRow($comment);
        return $result;
    }
    
    function getAllCommentidsByUser() {

        $session = Model_StaticFunctions::sessionContent();

        $ids = $this->_db->select()->from($this->_name,"id")->where("userid=?",$session->id);
        $result = $this->getAdapter()->fetchCol($ids);
        return $result;
    }

    function deleteComment($commentid) {

        $session = Model_StaticFunctions::sessionContent();

        $subj = $this->_db->select()->from($this->_name,array("subjid","subjtype","userid"))->where("id=?",$commentid);
        $subj = $this->_db->fetchRow($subj);

        if ($subj["subjtype"] == 3) {
            $elmAuthorid = $this->_db->select()->from("reply","userid")->where("id=?", $subj["subjid"]);
            $elmAuthorid = $this->_db->fetchOne($elmAuthorid);
        }elseif($subj["subjtype"] == 4) {
            $elmAuthorid = $this->_db->select()->from("photo","userid")->where("id=?", $subj["subjid"]);
            $elmAuthorid = $this->_db->fetchOne($elmAuthorid);
        }

        if($elmAuthorid == $session->id || $subj["userid"] == $session->id) {
            $where = $this->_db->quoteInto("id=?",$commentid);

            $this->_db->delete($this->_name,$where);

            if($subj["subjtype"] == 3) {
                $relType = 4;
            }elseif($subj["subjtype"] == 4) {
                $relType = 7;
            }
            $notifTable = new Logged_Model_DbTable_Notif();
            $notifTable->deleteNotif(FALSE,$commentid, $relType);
        }
    }

}
