<?php

class Logged_Model_DbTable_Scape extends Zend_Db_Table_Abstract {

    protected $_name = "scape";

    function createScape ($content,$title,$tags=NULL,$private=NULL) {

        if($content && $content != "" || $title != "") {
            $session = Model_StaticFunctions::sessionContent();
            $userid = $session->id;


            $create = $this->_db->insert($this->_name, array(
                    "content" => $content,
                    "title" => $title,
                    "userid" => $userid,
                    "time" => gmdate("Y-m-d H:i:s", time())));

        }
        $scapeid = $this->_db->lastInsertId($this->_name);
        $GLOBALS["jsonResponce"]["scapeid"] = $scapeid;
        
        // Scapebox entry
        if($tags){
        
        $scapeboxTable = new Logged_Model_DbTable_Scapebox();
        foreach($tags as $userid){
        
        $scapeboxTable->createEntry($scapeid,$userid,$GLOBALS["session"]->id,$private);
                 
       }
     }

        $this->_db->insert("scape_search", array(
                "scapeid"=>$scapeid,
                "content"=>strip_tags($content),
                "title"=>$title));


    }

    function getAllScapes () {

        $post = $this->_db->select()->from($this->_name)
                ->join("users", "scape.userid = users.id", array("CONCAT(`users`.`firstname`,' ',`users`.`lastname`) AS `fullname`","username"));
        $result = $this->getAdapter()->fetchAll($post);
        return $result;

    }
    
    function searchInsert($scapeid,$content,$title){
                $this->_db->insert("scape_search", array(
                "scapeid"=>$scapeid,
                "content"=>strip_tags($content),
                "title"=>$title));
    }
    
    function getUserScapes ($userid,$loadMore = NULL) {

        if ($loadMore) {
            $loadMore = "$loadMore,";
        }
        $scape = $this->_db->query("
                SELECT `scape`.*,
                (SELECT CONCAT(\"[\",GROUP_CONCAT(CONCAT(`receiverid`)),\"]\") FROM scapebox_receive WHERE scapebox_receive.scapeid = scape.id) AS receiverids,
                CONCAT(`users`.`firstname`,' ',`users`.`lastname`) AS `fullname`,
                `users`.`username`,
                (SELECT COUNT(*) FROM `reply` WHERE `reply`.`scapeid` = `scape`.`id`) AS `replyCount`
                FROM `scape` INNER JOIN `users` ON `scape`.`userid` = `users`.`id`
                WHERE `scape`.`userid` = $userid
                ORDER BY `time` DESC
                LIMIT $loadMore 10");

        $results = $scape->fetchAll();

        return $results;
    }

    function getScapebyId ($scapeid) {


        $scape = $this->_db->select()->from($this->_name,array("*",
                        "(SELECT CONCAT(\"[\",GROUP_CONCAT(CONCAT(`receiverid`)),\"]\") FROM scapebox_receive WHERE scapebox_receive.scapeid = scape.id) AS receiverids"))->where("id=?", $scapeid);
        $result = $this->getAdapter()->fetchRow($scape);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    function getLatestScapes($loadMore = NULL) {
        $watchlistTable = new Logged_Model_DbTable_Watchlist();
        $targetids = $watchlistTable->getAllTargetIds();

        $session = Model_StaticFunctions::sessionContent();
        $userid = $session->id;

        $targetids[] = $userid;
        $targetids = implode(",",$targetids);

        if ($loadMore) {
            $loadMore = "$loadMore,";
        }


        $scape = $this->_db->query("SELECT `scape`.*,
     CONCAT(`users`.`firstname`,' ',`users`.`lastname`) AS `fullname`,
     (SELECT CONCAT(\"[\",GROUP_CONCAT(CONCAT(`receiverid`)),\"]\") FROM scapebox_receive WHERE scapebox_receive.scapeid = scape.id) AS receiverids,
    # (SELECT private FROM scapebox_receive WHERE scapebox_receive.scapeid = scape.id LIMIT 1) AS private,
     `users`.`username`,
# #  `privacy_custom`.*,
# #  `spc`.`hide` as spchide,
# #   `spc`.`show` as spcshow,

    (SELECT COUNT(*) FROM `reply` WHERE `reply`.`scapeid` = `scape`.`id`) AS `replyCount`

     FROM `scape`

  #   LEFT JOIN `scape_privacy_custom` AS `spc` ON `spc`.`scapeid` = `scape`.`id`

     RIGHT JOIN `watchlist` AS `w` ON `w`.`userid` = '$userid' AND `w`.`targetid` = `scape`.`userid` OR `scape`.`userid`= '$userid'

     INNER JOIN `users` ON `scape`.`userid` = `users`.`id`

  #   LEFT JOIN `privacy_custom` ON `privacy_custom`.`userid` = `scape`.`userid` AND `privacy_custom`.`subject`='scape'

     WHERE (`scape`.`id` IS NOT NULL)
     
  #   AND IF(((SELECT private FROM scapebox_receive WHERE scapebox_receive.scapeid = scape.id LIMIT 1)=1),false,true)

     # scape custom privacy where.-------

  #   AND (IF((`spc`.`hide` IS NULL AND `spc`.`show` IS NULL OR '$userid' NOT IN (SELECT `hide` FROM `scape_privacy_custom` WHERE `scape_privacy_custom`.`scapeid`=`scape`.`id`) OR `spc`.`show` = '$userid'),TRUE,FALSE))

     # custom privacy-------

  #   AND (IF((`spc`.`hide` IS NULL AND `spc`.`show` IS NULL),(`privacy_custom`.`show` IS NULL AND `privacy_custom`.`hide` IS NULL OR '$userid' NOT IN (SELECT `hide` FROM `privacy_custom` WHERE `privacy_custom`.`userid`=`scape`.`userid` AND `privacy_custom`.`subject`='scape') OR `privacy_custom`.`show` = '$userid'),TRUE))

     # privacy ------

  #   AND (IF(((SELECT `scape` FROM `privacy_global` WHERE `privacy_global`.`userid` = `scape`.`userid`) OR (`spc`.`hide` IS NOT NULL OR `spc`.`show` IS NOT NULL)),TRUE,(SELECT `scape` FROM `privacy_global` WHERE `privacy_global`.`userid` = `scape`.`userid` )) OR `scape`.`userid` = '$userid') OR (`scape`.`userid` = '$userid')

     GROUP BY `scape`.`id`

     ORDER BY `time` DESC

     LIMIT $loadMore 10");

        $results = $scape->fetchAll();

        return $results;

    }

    function updateScape($scapeid) {

        $scape = $this->_db->select()->from($this->_name,array("*",
                                    "(SELECT CONCAT(\"[\",GROUP_CONCAT(CONCAT(`receiverid`)),\"]\") FROM scapebox_receive WHERE scapebox_receive.scapeid = scape.id) AS receiverids"))->join("users", "`scape`.`userid`=`users`.`id`", array("CONCAT(`firstname`, ' ' ,`lastname`) AS fullname","username"))->where("`scape`.`id`=?",$scapeid);
        $result = $this->getAdapter()->fetchRow($scape);
        return $result;

    }

    function editScape($content,$title,$scapeid) {
        $session = Model_StaticFunctions::sessionContent();
        // Scape table updates
        if($this->_db->update($this->_name,array("content" => $content,"title" => $title),"`id`={$scapeid} AND `userid`={$session->id}")) {
            // Scape search index table updates aswell
            $this->_db->update("scape_search",array("content" => strip_tags($content),"title" => $title),$this->_db->quoteInto("scapeid=?",$scapeid));
           // $scape = $this->_db->select()->from($this->_name)->where("id=?",$scapeid);
          //  $result = $this->getAdapter()->fetchRow($scape);
          //  return $result;
        }
    }

    function getUserid($scapeid) {

        $userid = $this->_db->select()->from($this->_name,"userid")->where("id=?",$scapeid)->limit("1");
        $result = $this->getAdapter()->fetchOne($userid);
        return $result;
    }
    
    function getUsername($scapeid) {

        $query = $this->_db->select()->from($this->_name,"userid")->join("users","`scape`.`userid`=`users`.`id`","username")->where("scape.id=?",$scapeid)->limit("1");
        $result = $this->getAdapter()->fetchRow($query);
        $result = $result["username"];
        return $result;
    }
    
    function getAllScapeidsByUser($userid) {

        $ids = $this->_db->select()->from($this->_name,"id")->where("userid=?",$userid);
        $result = $this->getAdapter()->fetchCol($ids);
        return $result;
    }

    function search($searchQuery,$offset){
        
     $searchQuery = $this->_db->quoteInto("?",$searchQuery);
     $offset = $this->_db->quoteInto("?",$offset);
        
       $result = $this->_db->query("
         SELECT `scape_search`.*,
        `users`.`id` AS `userid`,
        `users`.`username`AS `username`,
         CONCAT(`users`.`firstname`,' ',`users`.`lastname`) AS `fullname`,
        `scape`.`time` AS `time`,
         (SELECT COUNT(id) FROM `scape_search` WHERE MATCH (`scape_search`.`content`,`scape_search`.`title`) AGAINST ($searchQuery IN NATURAL LANGUAGE MODE)) AS `total`

     FROM `scape_search`,`users`,`scape`
     WHERE MATCH (`scape_search`.`content`,`scape_search`.`title`)
     AGAINST ($searchQuery IN NATURAL LANGUAGE MODE)
     #AND IF ((SELECT scapebox_receive.id FROM scapebox_receive WHERE scapebox_receive.scapeid=scape_search.scapeid AND scapebox_receive.receiverid != {$GLOBALS["session"]->id} AND scapebox_receive.private=1),FALSE,TRUE)
     AND `scape`.`id` = `scape_search`.`scapeid`
     AND `users`.`id` = `scape`.`userid` LIMIT $offset,15")->fetchAll();
        
        return $result;
    }

    function deleteScape($scapeid) {
        $session = Model_StaticFunctions::sessionContent();
        // Delete scape
        if($this->_db->delete($this->_name,"`id`={$scapeid} AND `userid`={$session->id}")) {
            // Delete search entry for scape
            $this->_db->delete("scape_search",$this->_db->quoteInto("scapeid=?",$scapeid));
            // Delete replies on scape
            $repiesTable = new Logged_Model_DbTable_Reply();
            $repiesTable->deleteReply(FALSE,$scapeid);

            $where = "`subjid`=$scapeid AND `subjtype`=2";

            $this->_db->delete("like",$where);
            $this->_db->delete("dislike",$where);

            $this->_db->delete("notif","`subjid`={$scapeid} AND `reltype` IN (1,2,3)");
        }


    }


}