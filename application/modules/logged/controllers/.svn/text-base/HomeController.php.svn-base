<?php

class Logged_HomeController extends Zend_Controller_Action {

    public function init() {
        Zend_Layout::getMvcInstance()->setLayoutPath(APPLICATION_PATH . "/modules/logged/layouts");

        // TEMPORARY
        $session = Model_StaticFunctions::sessionContent();
             if(!$session){
               $this->_redirect("/login");
             }
    }

    public function indexAction() {

        if ($this->_request->isXmlHttpRequest()) {
            $this->_helper->layout()->disableLayout();
        }
        // If user does not have a session and is logged out he has no bussiness being here ... REDIRECT.
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect("index");
        }
        
        $this->view->headTitle("Scapehouse | Home", "SET");
        $this->view->pageClass = "homepage loggedIn";

        // Pushing the Users full name on to the view.


        $sessionContent = Model_StaticFunctions::sessionContent();

        $this->view->userFullname = $sessionContent->fullname;
        $this->view->userid = $sessionContent->id;


        
    }

}

