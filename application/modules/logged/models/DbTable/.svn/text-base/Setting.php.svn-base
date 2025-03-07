<?php

class Logged_Model_DbTable_Setting extends Zend_Db_Table_Abstract {

//    protected $_name = "note";
    
    function saveTheme ($userid,$theme) {
        
        switch($theme){
            case "black":
                $theme = 1;
               break;
            case "green":
                $theme = 2;
                break;
            case "blue":
                $theme = 3;
                break;
            case "red":
                $theme = 4;
                break;
            case "pink":
                $theme = 5;
                break;
            default:
              $theme = null;
            break;
        }
        
        $this->_db->delete("setting_personalize","userid=$userid");
        
        if($theme && $theme != 2){
            
        $this->_db->insert("setting_personalize", array(
                "userid" => $userid,
                "theme" => $theme));
                           
        }
    
    }

     function getTheme($userid){
            $query = $this->_db->select()->from("setting_personalize")->where("userid=?",$userid);
            return $this->_db->fetchRow($query);
    }
        
}