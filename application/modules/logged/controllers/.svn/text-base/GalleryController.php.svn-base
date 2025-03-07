<?php

class Logged_GalleryController extends Zend_Controller_Action {

    public function init() {
        if(!$GLOBALS["session"]){
            $this->_redirect("/login?redirect={$_SERVER["REQUEST_URI"]}");
            die;
        }
        Zend_Layout::getMvcInstance()->setLayoutPath(APPLICATION_PATH . "/modules/logged/layouts");
    }

    public function indexAction() {

        $session = Model_StaticFunctions::sessionContent();
        $username  = $this->_getParam("username");
        $this->view->page = rawurlencode($_GET['page']);

        $userTable = new Model_DbTable_Users();
        $user = $userTable->getUserByUsername($username);
        $this->view->assign($user);

        if($session->id == $user["id"]){
          $this->view->headTitle("Scapehouse | My Profile Pictures", "SET");
        }else{
          $this->view->headTitle("{$this->view->fullname}'s Profile Pictures | Scapehouse", "SET");
        }

        $this->view->pageClass = "photoGallery loggedIn";
    }


    public function photoAction() {

        $photoid = $this->_getParam("id");
        $username  = $this->_getParam("username");

        $userTable = new Model_DbTable_Users();
        $user = $userTable->getUserByUsername($username);
        $this->view->assign($user);

        $this->view->photoid = $photoid;
        $this->view->headTitle("Scapehouse | Photos", "SET");
        $this->view->pageClass = "photo loggedIn";
    }


    public function displayphotoAction() {

        $photoTable =  new Logged_Model_DbTable_Photo();

        // No view action---
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        // ---
            $elmid = $_GET["elmid"];
            $idType = $_GET["idType"];
            $current = $_GET["current"];
            $size = $_GET["size"];
            $quality = $_GET["quality"];

            $photos =  $photoTable->getPhotos($elmid,$idType,$current);

    }
}
?>
