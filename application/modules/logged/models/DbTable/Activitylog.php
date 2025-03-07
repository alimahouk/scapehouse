<?php

class Logged_Model_DbTable_Activitylog extends Zend_Db_Table_Abstract {

    protected $_name = "activitylog";
    
    function record($userid){
        $this->_db->insert($this->_name,array("userid"=>$userid,"time" => gmdate("Y-m-d H:i:s", time())));
    }
    
    function deleteOld(){
        $time = gmdate("Y-m-d H:i:s", time()-180);
        $this->_db->delete($this->_name,"time < '$time'");
    }
    
    function getActive(){
        
        $time = gmdate("Y-m-d H:i:s", time()-180);
        
      /* echo$query = $this->_db->select()
        ->from($this->_name,"userid")
        ->join("users","users.id = activitylog.userid", array("firstname","lastname","username"))
        //->join("watchlist","watchlist.userid=activitylog.userid OR watchlist.targetid={$GLOBALS["session"]->id}")
        //->where("watchlist.userid=activitylog.userid AND watchlist.targetid={$GLOBALS["session"]->id}")
        //->where("watchlist.userid={$GLOBALS["session"]->id} AND watchlist.targetid=activitylog.userid")
        ->where("activitylog.time > '$time'")
        ->where("activitylog.userid !=?", $GLOBALS["session"]->id)
        ->group("activitylog.userid")->limit(8);
      */
      
      $query = $this->_db->query("
SELECT `activitylog`.`userid`, `users`.`firstname`, `users`.`lastname`, `users`.`username`
FROM `activitylog`
INNER JOIN `users` ON users.id = activitylog.userid

WHERE IF((SELECT watchlist.id from watchlist WHERE watchlist.userid=activitylog.userid AND watchlist.targetid={$GLOBALS["session"]->id}),TRUE,FALSE) AND
IF((SELECT watchlist.id from watchlist WHERE watchlist.userid={$GLOBALS["session"]->id} AND watchlist.targetid=activitylog.userid),TRUE,FALSE) AND

(activitylog.time > '$time') AND
(activitylog.userid !={$GLOBALS["session"]->id})

GROUP BY `activitylog`.`userid`
LIMIT 10");
      
        return $query->fetchAll();
    }
}