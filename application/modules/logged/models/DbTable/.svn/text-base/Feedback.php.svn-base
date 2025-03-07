<?php

class Logged_Model_DbTable_Feedback extends Zend_Db_Table_Abstract {

    protected $_name = "feedback";

    function save($type,$content,$thumbsup,$thumbsdown) {
        

        if($type != "" && $content != "") {

            $session = Model_StaticFunctions::sessionContent();
            
            if($thumbsup == "true") {
                $thumb = 1;
            }elseif($thumbsdown == "true") {
                $thumb = 0;
            }
            $content = htmlentities($content);
            $this->_db->insert($this->_name, array(
                "userid"=>$session->id,
                "type"=>$type,
                "content"=>$content,
                "thumb"=>$thumb,
                "time" => gmdate("Y-m-d H:i:s", time())
                ));
        }
    }

}
