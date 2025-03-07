<?php

class Logged_Model_DbTable_Notif extends Zend_Db_Table_Abstract {

    //Var Declaration
    protected $_name = "notif";

    function createNotif($reciverid ,$action, $actionid, $subject, $subjectid) {

        $session = Model_StaticFunctions::sessionContent();
        $userid = $session->id;
        if($userid != $reciverid) {
            switch (true) {
                case $action == "reply" && $subject == "scape":
                    $relType = 1;
                    break;
                case $action == "like" && $subject == "scape":
                    $relType = 2;
                    break;
                case $action == "dislike" && $subject == "scape":
                    $relType = 3;
                    break;
                case $action == "comment" && $subject == "reply":
                    $relType = 4;
                    break;
                case $action == "like" && $subject == "reply":
                    $relType = 5;
                    break;
                case $action == "dislike" && $subject == "reply":
                    $relType = 6;
                    break;
                case $action == "comment" && $subject == "photo":
                    $relType = 7;
                    break;
                case $action == "like" && $subject == "photo":
                    $relType = 8;
                    break;
                case $action == "dislike" && $subject == "photo":
                    $relType = 9;
                    break;
                case $action == "watch" && $subject == "user":
                    $relType = 10;
                    break;
                case $action == "reqaccept" && $subject == "user":
                    $relType = 11;
                    break;
                case $action == "note" && $subject == "user":
                    $relType = 12;
                    break;

            }

            //Logged_Model_NotifMailer::send($userid,$reciverid,$actionid,$subjectid,$relType);

            $this->_db->insert($this->_name, array(
                    "userid" => $userid,
                    "reciverid" => $reciverid,
                    "actionid" => $actionid,
                    "subjid" => $subjectid,
                    "reltype" => $relType,
                    "imp" => 0,
                    "time" => gmdate("Y-m-d H:i:s", time())
            ));
            
           // $GLOBALS["jsonResponce"]["notifUrl"] = "/logged/ajax/notifmailer?userid=$userid&reciverid=$reciverid&actionid=$actionid&subjid=$subjectid&relType=$relType";

        }
    }

    function getNotifs() {

        $session = Model_StaticFunctions::sessionContent();
        $userid = $session->id;

        $query = <<<QUERY
        SELECT

        CONCAT("[",GROUP_CONCAT(CONCAT(`id`)),"]") AS `id`,
        # subjidx = subjid external
        `subjid` as `subjidx`,
        `imp` as `impx`,
        MAX(`time`) AS `time`,
        # rtx = reltype external
        `reltype` as `rtx`,
        (SELECT CONCAT("[",GROUP_CONCAT(CONCAT(`userid`)),"]")  FROM `notif` WHERE `reltype`=`rtx` AND `reciverid`=$userid AND `subjid`=`subjidx` AND `imp`=`impx`) AS `senderid`,
        (SELECT CONCAT("[",GROUP_CONCAT(CONCAT(`actionid`)),"]")  FROM `notif` WHERE `reltype`=`rtx` AND `reciverid`=$userid AND `subjid`=`subjidx` AND `imp`=`impx`) AS `actionid`

        FROM `notif`

        WHERE `reciverid`=$userid

        GROUP BY `subjid`, `rtx`, `impx`

        ORDER BY `time` DESC

        LIMIT 30
QUERY;

        $notifs = $this->_db->query($query)->fetchAll();

        $this->_db->query("UPDATE `$this->_name` SET `imp`=`imp`+1 WHERE `reciverid`=$userid");

        return $notifs;

    }

    function countNotifs() {

        $session = Model_StaticFunctions::sessionContent();
        $userid = $session->id;

        return count($this->_db->query("SELECT COUNT(`id`) as `count` FROM `notif` WHERE `reciverid`=$userid AND `imp`=0 GROUP BY `subjid`, `reltype`")->fetchAll(NULL, "count"));
    }

    function deleteNotif($notifid=FALSE,$actionid=FALSE,$relType=FALSE) {


        if($notifid) {
            $this->_db->delete($this->_name, $this->_db->quoteInto("id=?", $notifid));
        }elseif(isset($actionid,$relType)) {

            $session = Model_StaticFunctions::sessionContent();
            $userid = $session->id;
            $this->_db->delete($this->_name, "`actionid`=$actionid AND `userid`=$userid AND `reltype`=$relType");
        }
    }

    function deleteNotifs($notifids) {

        $allow = TRUE;

        $notifids = json_decode($notifids);

        foreach($notifids as $notifid) {
            if(!is_numeric($notifid)) {
                $allow = FALSE;
            }
        }
        if ($allow) {
            $session = Model_StaticFunctions::sessionContent();
            $userid = $session->id;

            $notifids = implode(",",$notifids);
            $this->_db->delete($this->_name, "`id` IN ($notifids) AND `reciverid`=$userid");
        }

    }


}
?>
