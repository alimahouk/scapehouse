<?php

class Model_DbTable_Users extends Zend_Db_Table_Abstract {

    protected $_name = "users";

    function createUser ($firstname,$lastname, $email, $username, $password) {
        $hashedPassword = Model_StaticFunctions::salted_sha1($password);
        $create = $this->_db->insert($this->_name, array(
                "firstname" => $firstname,
                "lastname" => $lastname,
                "email" => $email,
                "username" => $username,
                "password" => $hashedPassword,
                "time" => gmdate("Y-m-d H:i:s", time()))
        );
        $userid = $this->_db->lastInsertId($this->_name);

        // Login user ----
        $login = new Model_Login();

        $login->username = $username;
        $login->password = $password;
        $login->dsLogin();
        
        // create entry in users_search table
        $fullname = $firstname . " " . $lastname;
        $this->_db->insert("users_search", array("userid" => $userid, "fullname" => $fullname));

        return $userid;
    }
    
    function updateName($userid,$firstname,$lastname){
        
        $this->_db->update($this->_name,array("firstname" => $firstname, "lastname" => $lastname),"id={$userid}");
        
        $fullname = $firstname . " " . $lastname;
        $this->_db->update("users_search", array("fullname" => $fullname),"userid={$userid}");
        
        $query = $this->_db->select()->from($this->_name,array("username","password"))->where("id=?", $userid);
        $result = $this->_db->fetchRow($query);
        
        // Login user with updated name----
        $login = new Model_Login();

        $login->username = $result["username"];
        $login->password = $result["password"];
        $login->dsLogin(true);

    }
    
    function checkPassword($userid,$password){
        $query = $this->_db->select()->from($this->_name, "id")->where("id=?", $userid)->where("password=?", $password);
        return $result = $this->_db->fetchOne($query);
    }
    
    function updatePassword($userid,$password){
        $this->_db->update($this->_name,array("password"=>$password),"id={$userid}");
    }
    
    function checkUniqueUser ($username) {

        $select = $this->_db->select()->from($this->_name, array("id"))->where("username=?", $username);
        $result = $this->getAdapter()->fetchOne($select);
        if ($result) {
            return true;
        }
        return false;
    }

    function checkUniqueEmail ($email) {

        $select = $this->_db->select()->from($this->_name, array("id"))->where("email=?", $email);
        $result = $this->getAdapter()->fetchOne($select);
        if ($result) {
            return true;
        }
        return false;
    }

    function getUserByUsername ($username,$fetchAll = FALSE) {

        $select = $this->_db->select()->from($this->_name,array("id","CONCAT(`firstname`, ' ' ,`lastname`) AS fullname","firstname","lastname","username","email"))->where("username=?", $username);
        if($fetchAll) {
            $result = $this->getAdapter()->fetchAll($select);
        }else {
            $result = $this->getAdapter()->fetchRow($select);
        }

        if ($result) {
            return $result;
        }
        return false;
    }

    function getUserByEmail ($email) {

        $select = $this->_db->select()->from($this->_name,array("id","CONCAT(`firstname`, ' ' ,`lastname`) AS fullname","firstname","lastname","username","email"))->where("email=?", $email);
        $result = $this->getAdapter()->fetchAll($select);
        if ($result) {
            return $result;
        }
        return false;
    }

    function getUserById ($id) {

        $select = $this->_db->select()->from($this->_name,array("id","CONCAT(`firstname`, ' ' ,`lastname`) AS fullname","firstname","lastname","username","email"))->where("id=?", $id);
        $result = $this->getAdapter()->fetchRow($select);
        if ($result) {
            return $result;
        }
        return false;
    }
    
     function getUsernameById($id) {
        $select = $this->_db->select()->from($this->_name,"username")->where("id=?", $id);
        $result = $this->getAdapter()->fetchOne($select);
        return $result;
    }       

    function getUserTime ($id) {

        $select = $this->_db->select()->from($this->_name,"time")->where("id=?", $id);
        $result = $this->getAdapter()->fetchOne($select);
        if ($result) {
            return $result;
        }
        return false;
    }

    function getAllUsers () {

        $select = $this->_db->select()->from($this->_name,array("id","CONCAT(`firstname`, ' ' ,`lastname`) AS fullname","firstname","lastname","username","email"));
        $result = $this->getAdapter()->fetchAll($select);
        if ($result) {
            return $result;
        }
        return false;
    }
    
    function getRecentUsers(){
        
        $rand = rand(1,20);
        
        $select = $this->_db->select()->from($this->_name,array("id","CONCAT(`firstname`, ' ' ,`lastname`) AS fullname","firstname","lastname","username"))
        ->limit(3,$rand)
        ->where("id!=?",$GLOBALS["session"]->id)
        ->where("IF((SELECT watchlist.id FROM watchlist WHERE targetid = users.id AND watchlist.userid={$GLOBALS["session"]->id}),FALSE,TRUE)")
        ->order("id DESC");
        return $this->_db->fetchAll($select);
    }


    function searchUsersByName ($query,$limit,$offset,$fromWatchers = FALSE) {

        $session = Model_StaticFunctions::sessionContent();
        $userid = $session->id;
        if($fromWatchers) {
            $watchlistFrom = " `watchlist`, ";
            $watchlistWhere = " `watchlist`.`targetid`='$userid' AND `users_search`.`userid`=`watchlist`.`userid` AND ";
        }
        $select = $this->_db->query("SELECT
                                    `users_search`.`userid` AS `userid`,
                                    `users_search`.`fullname` AS `fullname`,
                                    `users`.`username`,
                                    (SELECT COUNT(`id`) FROM `users_search` WHERE MATCH(`users_search`.`fullname`) AGAINST(? IN BOOLEAN MODE)) AS `total`
                                    FROM
                                    `users_search`,
                                    $watchlistFrom
                                    `users`
                                    WHERE $watchlistWhere MATCH(`users_search`.`fullname`)
                                    AGAINST(? IN BOOLEAN MODE) AND `users_search`.`userid` = `users`.`id` LIMIT $offset,$limit", array($query."*",$query."*"));
        $result = $select->fetchAll();

        if ($result) {
            return $result;
        }
        return false;
    }

    function deleteUser($userid) {
        // delete user
        // delete entry in users_search aswell
        //$this->_db->insert("users_search", "`userid`='$userid'");
    }

}

