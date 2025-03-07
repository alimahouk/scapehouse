<?php

class Logged_Model_DbTable_Events extends Zend_Db_Table_Abstract {

    //Var Declaration
    protected $_name = "events";

    function createEvent ($event,$eventid,$subject,$subjectid,$subjectuserid) {

        $session = Model_StaticFunctions::sessionContent();
        $userid = $session->id;


        $create = $this->_db->insert($this->_name, array(
                    "userid" => $userid,
                    "event" => $event,
                    "eventid" => $eventid,
                    "subject" => $subject,
                    "subjectid" => $subjectid,
                    "subjectuserid" => $subjectuserid,
                    "time" => date("Y-m-d H:i:s",time())));
    }

    function getEventsBySubjectUserid($subjectuserid){

          $events = $this->_db->select()->from($this->_name)->where("subjectuserid=?", $subjectuserid);
          return $result = $this->getAdapter()->fetchAll($events);

    }

}
?>
