<?php

class VerifyController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        Zend_Layout::getMvcInstance()->setLayoutPath(APPLICATION_PATH . '/modules/default/layouts/');
    }

    public function indexAction() {

        // No view action---
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        // ---

        echo $_GET["accV"];
        if(preg_match("/[a-f0-9]{40,40}/", $_GET["accV"])) {
            // Verify
            $verifyTable = new Model_DbTable_Verify();
            $userdata = $verifyTable->verify($_GET["accV"]);
            // Send to home page

            $loginProcessor = new Model_Login();

            $loginProcessor->username = $userdata["username"];
            $loginProcessor->password = $userdata["password"];

            if($loginProcessor->dsLogin(true)) {
                $this->_redirect("/{$userdata["username"]}/home?new=true");
            }else {
               $this->_redirect("/index");
            }



        }else {
            $this->_redirect("/index");
        }
    }
}






