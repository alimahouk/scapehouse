<?php

class Logged_Model_DbTable_Like extends Zend_Db_Table_Abstract {

    protected $_name = "like";

    // Likes profiler ------
    // U stands for user OU stands for OtherUser.
    function like($elmid,$idType) {

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

        $where = $idType."id";

        $like = $this->_db->select()->from($this->_name,"id")->where("subjid=?", $elmid)->where("userid=?", $userid)->where("subjtype=?", $subjType);
        $id = $this->_db->fetchOne($like);

        if ($id) {

            $subjType = $this->_db->select()->from($this->_name,"subjtype")->where("id=?",$id);
            $subjType = $this->_db->fetchOne($subjType);

            $this->_db->delete($this->_name,$this->_db->quoteInto("id=?", $id));


            if($subjType == 3) {
                $relType = 5;
            }elseif($subjType == 4) {
                $relType = 8;
            }elseif($subjType == 2) {
                $relType = 2;
            }

            $notifTable = new Logged_Model_DbTable_Notif();
            $notifTable->deleteNotif(FALSE,$id, $relType);

        } else {

            $this->_db->insert($this->_name, array("userid" => $userid , "subjid" => $elmid, "subjtype" => $subjType));

            $likeid = $this->_db->lastInsertId($this->_name);

            $reciverid = $this->_db->select()->from($idType,"userid")->where("id=?",$elmid)->limit("1");
            $reciverid = $this->_db->fetchOne($reciverid);

            $notifTable = new Logged_Model_DbTable_Notif();
            $notifTable->createNotif($reciverid,"like", $likeid, $idType, $elmid);

        }
    }

    function getAllLikes ($elmid, $idType) {
        // memcache candidate -----

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

        $where = $idType."id";

        $sessionContent = Model_StaticFunctions::sessionContent();
        $userid = $sessionContent->id;

                        $query = <<<QUERY
        SELECT
CONCAT("[",GROUP_CONCAT(CONCAT('"',`u`.`username`,'"')),"]") AS `username`,
CONCAT("[",GROUP_CONCAT(CONCAT('"',`u`.`firstname`,' ',`u`.`lastname`,'"')),"]") AS `fullname`,
CONCAT("[",GROUP_CONCAT(CONCAT(`$this->_name`.`userid`)),"]") AS `userid`

FROM `$this->_name` INNER JOIN `users` AS `u` ON `$this->_name`.`userid` = `u`.`id` WHERE (`subjid`=$elmid) AND (`subjtype`=$subjType)
QUERY;
        $result = $this->_db->query($query)->fetch();
        
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    function getAllOULikes ($elmid, $idType) {

        // memcache candidate

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

        $sessionContent = Model_StaticFunctions::sessionContent();
        $userid = $sessionContent->id;

        $where = $idType."id";

        $like = $this->_db->select()->from($this->_name)->where("subjid=?", $elmid)->where("userid !=?", $userid)->where("subjtype=?",$subjType);
        $result = $this->_db->fetchAll($like);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    function likeOffOnDislike ($elmid, $idType) {

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

        $sessionContent = Model_StaticFunctions::sessionContent();
        $userid = $sessionContent->id;

        $where = $idType."id";

        $like = $this->_db->select()->from($this->_name,"id")->where("subjid=?", $elmid)->where("userid=?", $userid)->where("subjtype=?",$subjType);
        $id = $this->_db->fetchOne($like);

        if ($id) {
            
            $subjType = $this->_db->select()->from($this->_name,"subjtype")->where("id=?",$id);
            $subjType = $this->_db->fetchOne($subjType);

            $this->_db->delete($this->_name,$this->_db->quoteInto("id=?", $id));


            if($subjType == 3) {
                $relType = 5;
            }elseif($subjType == 4) {
                $relType = 8;
            }elseif($subjType == 2) {
                $relType = 2;
            }

            $notifTable = new Logged_Model_DbTable_Notif();
            $notifTable->deleteNotif(FALSE,$id, $relType);

        }

    }

// Likes profiler END ------


}
