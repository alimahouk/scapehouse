<?php

class Logged_Model_DbTable_NotifSubject extends Zend_Db_Table_Abstract {

    //Var Declaration
    protected $_name = "notif_subject";

    function createNotifSubject($notifActionid,$subject,$subjectid) {

        $session = Model_StaticFunctions::sessionContent();
        $userid = $session->id;

        $this->_db->insert($this->_name, array(
            "notif_actionid" => $notifActionid,
            "{$subject}id" => $subjectid,
        ));

    }

}
?>
