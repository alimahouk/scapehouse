<?php

class Logged_ScapeboxController extends Zend_Controller_Action {

    public function init() {
        Zend_Layout::getMvcInstance()->setLayoutPath(APPLICATION_PATH . "/modules/logged/layouts");

             if(!$GLOBALS["session"]){
               $this->_redirect("/login");
             }
    }

    public function otherAction() {

        if ($this->_request->isXmlHttpRequest()) {
            $this->_helper->layout()->disableLayout();
        }
        // If user does not have a session and is logged out he has no bussiness being here ... REDIRECT.
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect("index");
        }
        
        $this->view->headTitle("Scapehouse | Scapebox", "SET");
        $this->view->pageClass = "scapebox loggedIn";
        
        $scapeboxTable = new Logged_Model_DbTable_Scapebox();
        $this->view->scapes = $scapeboxTable->getOthersScapes($GLOBALS["session"]->id);
        $this->view->newScapeCount = $scapeboxTable->newScapeCount();

        
    }


    public function meAction() {

        if ($this->_request->isXmlHttpRequest()) {
            $this->_helper->layout()->disableLayout();
        }
        // If user does not have a session and is logged out he has no bussiness being here ... REDIRECT.
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect("index");
        }
        
        $this->view->headTitle("Scapehouse | Scapebox", "SET");
        $this->view->pageClass = "scapebox loggedIn";
        
        $scapeboxTable = new Logged_Model_DbTable_Scapebox();
        $this->view->scapes = $scapeboxTable->getMyScapes($GLOBALS["session"]->id);
        $this->view->newScapeCount = $scapeboxTable->newScapeCount();

        
    }
}

