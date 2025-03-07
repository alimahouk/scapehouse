<?php

class Logged_Model_DbTable_Watchlist extends Zend_Db_Table_Abstract {

    protected $_name = "watchlist";

    function watch($targetid,$userid=FALSE) {
        if(!$userid) {
            $session = Model_StaticFunctions::sessionContent();
            $userid = $session->id;
        }
        if($targetid) {
            $select = $this->_db->select()->from($this->_name)->where("targetid=?", $targetid)->where("userid=?", $userid);
            $result = $this->_db->fetchOne($select);

            if (!$result) {
                $this->_db->insert($this->_name, array("targetid" => $targetid, "userid" => $userid));
                $watchid = $this->_db->lastInsertId("watchlist");
                $notifTable = new Logged_Model_DbTable_Notif();
                $notifTable->createNotif($targetid, "watch", $watchid, "user", $targetid);
            }
        }
    }
    function getUserWatch($targetid) {

        $session = Model_StaticFunctions::sessionContent();
        $userid = $session->id;

        $select = $this->_db->select()->from($this->_name)->where("targetid=?", $targetid)->where("userid=?", $userid);
        return $result = $this->_db->fetchRow($select);
    }

    function getWatching($userid,$limit=0) {

        $select = $this->_db->select()
                ->from($this->_name)
                ->join(array("u"=>"users"), "$this->_name.targetid = u.id", array("CONCAT(`firstname`, ' ' ,`lastname`) AS fullname","username"))
                ->where("watchlist.userid=?",$userid)
                ->order("fullname")->limit($limit);

        return  $result = $this->_db->fetchAll($select);

    }

    function getWatchers($userid,$limit=0) {

        $select = $this->_db->select()->from($this->_name)
                ->join(array("u"=>"users"), "$this->_name.userid = u.id", array("CONCAT(`firstname`, ' ' ,`lastname`) AS fullname","username"))
                ->order("fullname")->where("targetid=?", $userid)->limit($limit);

        return $result = $this->_db->fetchAll($select);

    }

    function ignore($watchid,$profile=FALSE) {

        $session = Model_StaticFunctions::sessionContent();
        $userid = $session->id;

        if($profile) {

            $this->_db->delete($this->_name, "targetid=$watchid AND userid=$userid");

            $this->_db->delete("notif","`reciverid`=$watchid AND `userid`=$userid AND `reltype`=10");
        }else {
            $this->_db->delete($this->_name, "id=$watchid AND userid=$userid");
            $notifTable = new Logged_Model_DbTable_Notif();
            $notifTable->deleteNotif(FALSE, $watchid, 10);
        }

    }

    function getAllTargetIds() {

        $session = Model_StaticFunctions::sessionContent();
        $userid = $session->id;

        $select = $this->_db->select()->from($this->_name,'targetid')->where("userid=?", $userid);
        return $result = $this->_db->fetchCol($select);
    }
    
    function getAllUserIds() {

        $session = Model_StaticFunctions::sessionContent();
        $userid = $session->id;

        $select = $this->_db->select()->from($this->_name,'userid')->where("targetid=?", $userid);
        return $result = $this->_db->fetchCol($select);
    }

    function isWatching($userid,$targetid) {

        $select = $this->_db->select()->from($this->_name,'id')->where("userid=?", $userid)->where("targetid=?", $targetid);
        return $result = $this->_db->fetchOne($select);
    }

    function searchWatchers($query,$userid) {

        $select = $this->_db->query("SELECT
                                    users_search.fullname,
                                    users.username,
                                    watchlist.*
                                    FROM
                                    users_search,
                                    watchlist,
                                    users
                                    WHERE watchlist.targetid=? AND users_search.id=watchlist.userid AND users_search.id = users.id
                                    AND MATCH(users_search.fullname)
                                    AGAINST(? IN BOOLEAN MODE) LIMIT 20", array($userid,$query."*"));
        return $result = $select->fetchAll();
    }


    function searchWatching($query,$userid) {

        $select = $this->_db->query("SELECT
                                    users_search.fullname,
                                    users.username,
                                    watchlist.*
                                    FROM
                                    users_search,
                                    watchlist,
                                    users
                                    WHERE watchlist.userid=? AND users_search.id=watchlist.targetid AND users_search.id = users.id
                                    AND MATCH(users_search.fullname)
                                    AGAINST(? IN BOOLEAN MODE) LIMIT 20", array($userid,$query."*"));
        return $result = $select->fetchAll();
    }
}
?>
