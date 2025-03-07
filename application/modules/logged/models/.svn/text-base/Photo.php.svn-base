<?php

class Logged_Model_Photo {

    private $mimetype;
    private $location;
    private $caption;
    private $filename;
    private $filesize;


    public function save($photo) {


        if($photo['tmp_name']) {

            $file = getImageSize($photo['tmp_name']);
            $this->mimetype = $file['mime'];
            if ($this->mimetype == "image/jpeg" || $this->mimetype == "image/png" || $this->mimetype == "image/gif") {
                $valid = 1;
            }else {
                return "Invalid file type";
            }
        }else {
            return "File size exceded";
        }

        $this->location = $_POST['location'];
        $this->caption = $_POST['caption'];
        $this->filename = $_FILES["imageFile"]["name"];
        $this->filesize = $_FILES["imageFile"]["size"];

        if ($valid) {


            $photoTable = new Logged_Model_DbTable_Photo();

            $hashedName = Model_StaticFunctions::salted_sha1(microtime(true).str_shuffle("1234567890abcdefghijklmnopqrstuvwxyz!@#$%^&*"));
            $photoTable->createPhoto($this->filename, $this->filesize, $this->mimetype,$this->location,$this->caption,$hashedName);

            include "../wideimage/WideImage.php";


// Resizer ------
            $session = Model_StaticFunctions::sessionContent();

            $photoFull = WideImage::load(file_get_contents($photo["tmp_name"]));
            if(!is_dir("userphotos/{$session->username}/profile/")) {
                mkdir("userphotos/{$session->username}/profile/",0777,true);
            }
            $photoFull->resizeDown(640, 480)->saveToFile("userphotos/{$session->username}/profile/f_{$hashedName}.jpg","80");


            $sizes = array("s"=>75,"m"=>180);

            foreach($sizes as $sizeName => $size) {

                $photoResize = WideImage::load(file_get_contents($photo["tmp_name"]));

                switch(true) {
                    case($photoResize->getHeight() > $photoResize->getWidth()):

                        if($size < 80) {
                            $photoResize = $photoResize->resize($size, $size,'outside','down');
                            $photoResize = $photoResize->crop('center', 'center', $size, $size);
                        }else {
                            $photoResize = $photoResize->resize($size, $size,'outside','down');

                        }
                        break;

                    case($photoResize->getHeight() < $photoResize->getWidth()):

                        if($size < 80) {
                            $photoResize = $photoResize->resize($size, $size,'outside','down');
                            $photoResize = $photoResize->crop('center', 'center', $size, $size);
                        }else {
                            $photoResize = $photoResize->resize($size, $size,'inside','down');

                        }
                        break;

                    case($photoResize->getHeight() == $photoResize->getWidth()):

                        if($size < 80) {
                            $photoResize = $photoResize->resize($size, $size,'inside','down');
                            $photoResize = $photoResize->crop('center', 'center', $size, $size);
                        }else {
                            $photoResize = $photoResize->resize($size, $size,'inside','up');

                        }
                        break;
                }
                $photoResize->saveToFile("userphotos/{$session->username}/profile/{$sizeName}_{$hashedName}.jpg","80");
            }
            return "Upload complete!";

        }
    }
//{$photo->display($photoid,"photo","med")}
    public function display($id,$idType,$size="full") {

        switch ($size) {
            case "full":
                $size = "f";
                break;
            case "med":
                $size = "m";
                break;
            case "small":
                $size = "s";
                break;
        }
        if($id && $idType=="user") {

            $userid = $id;
            $photoTable = new Logged_Model_DbTable_Photo();
            $photoInfo = $photoTable->getCurrent($userid);
            if($photoInfo) {
                return "/userphotos/{$photoInfo["username"]}/profile/{$size}_{$photoInfo["hash"]}.jpg";
            }else {
                return "/userphotos/user_silhouette.gif";
            }

        }elseif($id && $idType=="photo") {
            $photoTable = new Logged_Model_DbTable_Photo();
            $photoInfo = $photoTable->getPhotos($id,"photo");
            return "/userphotos/{$photoInfo[0]["username"]}/profile/{$size}_{$photoInfo[0]["hash"]}.jpg";
        }
    }

 /*   static function displayStatic($id,$idType,$size="full") {

        switch ($size) {
            case "full":
                $size = "f";
                break;
            case "med":
                $size = "m";
                break;
            case "small":
                $size = "s";
                break;
        }
        if($id && $idType=="user") {

            $userid = $id;
            $photoTable = new Logged_Model_DbTable_Photo();
            $photoInfo = $photoTable->getCurrent($userid);
            if($photoInfo) {
                return "/userphotos/{$photoInfo["username"]}/profile/{$size}_{$photoInfo["hash"]}.jpg";
            }else {
                return "/userphotos/user_silhouette.gif";
            }

        }elseif($id && $idType=="photo") {
            $photoTable = new Logged_Model_DbTable_Photo();
            $photoInfo = $photoTable->getPhotos($id,"photo");
            return "/userphotos/{$photoInfo[0]["username"]}/profile/{$size}_{$photoInfo[0]["hash"]}.jpg";
        }
    } */
}
?>
