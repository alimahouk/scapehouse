<?php

class Logged_Model_DbTable_Reply extends Zend_Db_Table_Abstract {

    protected $_name = "reply";


    function createReply ($content, $scapeid) {
        $session = Model_StaticFunctions::sessionContent();

        $create = $this->_db->insert($this->_name, array(
                "content" => $content ,
                "userid" => $session->id,
                "scapeid" => $scapeid,
                "time" => gmdate("Y-m-d H:i:s", time())));

        $replyid = $this->_db->lastInsertId($this->_name);
        $GLOBALS["jsonResponce"]["replyid"] = $replyid;
        
        $scapeTable = new Logged_Model_DbTable_Scape();
        $reciverid = $scapeTable->getUserid($scapeid);
        
        $users = $this->getUsersForNotifier($scapeid);
        $users = array_unique($users);
        
        $notifTable = new Logged_Model_DbTable_Notif();
        
       if($users) {
        foreach($users as $userid){
            if($reciverid != $userid){ // This should prevent the owner of the scape getting notified twice
            $notifTable->createNotif($userid,"reply", $replyid, "scape", $scapeid);
            }
        }
       } 
        $notifTable->createNotif($reciverid,"reply", $replyid, "scape", $scapeid);

    }

    function postedReply($replyid) {

        if (is_numeric($replyid)) {
            $reply = $this->_db->query("
            SELECT `reply`.*,
            CONCAT(`users`.`firstname`,' ',`users`.`lastname`) AS `fullname`,
            `users`.`username`,
            (SELECT COUNT(`id`) FROM `scape` WHERE `scape`.`id` = `reply`.`scapeid` AND `scape`.`userid` = `reply`.`userid`) as `author`
            FROM `reply` INNER JOIN `users` ON `reply`.`userid` = `users`.`id`  WHERE `reply`.`id` = '$replyid' LIMIT 1");
            $results = $reply->fetch();
            return $results;
        }
    }

    function getUsersForNotifier($scapeid) {
        
        $query = $this->_db->select()->from($this->_name,"userid")->where("scapeid=?",$scapeid)->limit("4")->order("time DESC");
        $results = $this->_db->fetchCol($query);
        return $results;

    }
    
    function getReplyContentById($replyid){
        $query = $this->_db->select()->from($this->_name,"content")->where("id=?",$replyid);
        return $this->_db->fetchOne($query);
    }

    function getUserid($replyid) {
        $userid = $this->_db->select()->from($this->_name,"userid")->where("id=?",$replyid)->limit("1");
        $result = $this->getAdapter()->fetchOne($userid);
        return $result;
    }


    function getRepliesOnScape($scapeid,$limit = 15,$offset = 0) {

        $replies = $this->_db->query("
            SELECT `reply`.*,
            CONCAT(`users`.`firstname`,' ',`users`.`lastname`) AS `fullname`,
            `users`.`username`,
            (SELECT COUNT(`id`) FROM `comment` WHERE `comment`.`subjid` = `reply`.`id` AND `comment`.subjType = 3) AS `commentCount`,
            (SELECT COUNT(`id`) FROM `scape` WHERE `scape`.`id` = $scapeid AND `scape`.`userid` = `reply`.`userid`) as `author`
            FROM `reply` INNER JOIN `users` ON `reply`.`userid` = `users`.`id`  WHERE `reply`.`scapeid` = '$scapeid' ORDER BY `time` ASC LIMIT $offset,$limit");
        $results = $replies->fetchAll();
        return $results;

    }

    function countRepliesOnScape($scapeid) {

        $count = $this->_db->query("SELECT COUNT(`id`) as 'count' FROM `reply` WHERE `reply`.`scapeid` = '$scapeid'");
        $result = $count->fetch();
        return $result;

    }

    function editReply($content,$replyid) {
        $session = Model_StaticFunctions::sessionContent();

        if($this->_db->update($this->_name,array("content" => $content),"`id`={$replyid} AND `userid`={$session->id}")) {

            $replies = $this->_db->select()->from($this->_name)->where("id=?",$replyid);
            $result = $this->getAdapter()->fetchRow($replies);
            return $result;
        }
    }

   function getAllReplyidsByUser() {

        $session = Model_StaticFunctions::sessionContent();

        $ids = $this->_db->select()->from($this->_name,"id")->where("userid=?",$session->id);
        $result = $this->getAdapter()->fetchCol($ids);
        return $result;
    }

    function deleteReply($replyid=FALSE,$scapeid=FALSE) {

        if($scapeid) {
            $this->_db->query("
                 DELETE

                `reply`.*,
                `comment`.*,
                `like`.*,
                `dislike`.*,
                `notif`.*

                FROM `reply`

                LEFT JOIN `comment` ON `comment`.`subjid` = `reply`.`id` AND `comment`.`subjType` = 3

                LEFT JOIN `like` ON `like`.`subjid` = `reply`.`id` AND `like`.`subjType` = 3

                LEFT JOIN `dislike` ON `dislike`.`subjid` = `reply`.`id` AND `dislike`.`subjtype` = 3

                LEFT JOIN `notif` ON (`notif`.`actionid` = `reply`.`id` AND `notif`.`subjid`=$scapeid AND `notif`.`reltype` = 1) OR (`notif`.`subjid`=`reply`.`id` AND `notif`.`reltype` IN (4,5,6))


                WHERE

               `scapeid` = $scapeid
                    ");
        }elseif($replyid) {

            $session = Model_StaticFunctions::sessionContent();

            $scapeAuthorid = $this->_db->select()->from("scape","userid")->join("reply", "`reply`.`scapeid`=`scape`.`id`")->where("reply.id=?", $replyid);
            $scapeAuthorid = $this->_db->fetchOne($scapeAuthorid);

            $replyAuthorid = $this->_db->select()->from("reply","userid")->where("reply.id=?", $replyid);
            $replyAuthorid = $this->_db->fetchOne($replyAuthorid);

            if($session->id == $scapeAuthorid || $session->id == $replyAuthorid) {
                $where = $this->_db->quoteInto("id=?",$replyid);
                $this->_db->delete($this->_name,$where);
                $where = "`subjid`=$replyid AND `subjtype`=3";

                $this->_db->delete("like",$where);
                $this->_db->delete("dislike",$where);
                $this->_db->delete("comment",$where);
                $this->_db->delete("notif","`subjid`=$replyid AND `reltype` IN (4,5,6)");

                $notifTable = new Logged_Model_DbTable_Notif();
                $notifTable->deleteNotif(FALSE,$replyid,1);
            }
        }

    }

}