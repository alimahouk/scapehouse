<?php

class Logged_Model_DbTable_Photo extends Zend_Db_Table_Abstract {

    protected $_name = "photo";

    function createPhoto ($filename,$filesize,$mimetype,$location,$caption,$hash) {
        $session = Model_StaticFunctions::sessionContent();
        $userid = $session->id;

        $this->_db->update($this->_name,array("current"=>0),"userid=$userid");

        $this->_db->insert($this->_name, array(
                //  "filedata" => $filedata,
                "mimetype" => $mimetype,
                "filename" => $filename,
                "filesize" => $filesize,
                "userid" => $userid,
                "caption" => $caption,
                "location" => $location,
                "hash" => $hash,
                "current" => 1,
                "time" => gmdate("Y-m-d H:i:s", time())));

        return $this->_db->lastInsertId($this->_name);

    }

    function getPhotos($elmid,$idType,$current=NULL,$count=NULL,$offset=NULL) {

        switch (true) {
            case($elmid && $idType=="user" && $current):

                $photo = $this->_db->select()->from($this->_name)->where("userid=?",$elmid)->where("current=?",1);
                $result = $this->getAdapter()->fetchAll($photo);
                if($result) {
                    return $result;
                }else {
                    return NULL;
                }

                break;

            case($elmid  && $idType=="user" && !$current):

                $photo = $this->_db->select()->from($this->_name)->where("userid=?",$elmid)->limit($count, $offset);
                $result = $this->getAdapter()->fetchAll($photo);
                if($result) {
                    return $result;
                }else {
                    return NULL;
                }

                break;
            case($elmid && $idType=="photo" && !$current):

                $photo = $this->_db->select()->from($this->_name)->join("users", "`users`.`id`=`photo`.`userid`", "username")->where("`photo`.`id`=?",$elmid);
                $result = $this->getAdapter()->fetchAll($photo);
                if($result) {
                    return $result;
                }else {
                    return NULL;
                }

                break;
            default:
                return NULL;
        }

    }

    function makeCurrent($id) {

        $session = Model_StaticFunctions::sessionContent();
        $userid = $session->id;

        $this->_db->update($this->_name,array("current"=>0),"userid=$userid");
        $this->_db->query("UPDATE $this->_name SET $this->_name.current=1 WHERE id= $id AND userid= $userid");
    }

    function getCurrent($userid) {

        $photo = $this->_db->select()->from($this->_name,array("id","hash"))->join("users", "`users`.`id`=`photo`.`userid`", "username")->where("`photo`.`userid`=?",$userid)->where("current=?",1);
        $result = $this->getAdapter()->fetchRow($photo);
        return $result;
    }

    function getPhotoDetails($photoid) {

        if(is_numeric($photoid)) {
            $photo = $this->_db->select()->from($this->_name,array("id","hash","userid"))->where("id=?",$photoid);
            $result = $this->getAdapter()->fetchRow($photo);
            return $result;
        }
    }

    function getUserid($photoid) {
        $userid = $this->_db->select()->from($this->_name,"userid")->where("id=?",$photoid)->limit("1");
        $result = $this->getAdapter()->fetchOne($userid);
        return $result;
    }
    
    function getUsername($photoid) {
        $userid = $this->_db->select()->from($this->_name,"userid")->join("users","`users`.`id`=`photo`.`userid`","username")->where("photo.id=?",$photoid)->limit("1");
        $result = $this->getAdapter()->fetchRow($userid);
        return $result;
    }    

    function editDetails($photoid,$caption,$location) {

        $session = Model_StaticFunctions::sessionContent();
        $userid = $session->id;

        $this->_db->update($this->_name,array("caption" => $caption,"location" => $location),"`id`=$photoid AND `userid`= $userid");
    }

    function deletePhoto($id) {

        $session = Model_StaticFunctions::sessionContent();

        $hash = $this->_db->select()->from($this->_name,"hash")->where("id=?",$id)->where("userid=?", $session->id);
        $hash = $this->_db->fetchOne($hash);

        if($this->_db->delete($this->_name,"`id`={$id} AND `userid`={$session->id}")) {

            $where = "`subjid`=$id AND `subjtype`=4";

            $this->_db->delete("like",$where);
            $this->_db->delete("dislike",$where);
            $this->_db->delete("comment",$where);

            $this->_db->delete("notif","`subjid`=$id AND `reltype` IN (7,8,9)");

            unlink("userphotos/{$session->username}/profile/f_{$hash}.jpg");
            unlink("userphotos/{$session->username}/profile//m_{$hash}.jpg");
            unlink("userphotos/{$session->username}/profile//s_{$hash}.jpg");
        }


    }

}

?>
