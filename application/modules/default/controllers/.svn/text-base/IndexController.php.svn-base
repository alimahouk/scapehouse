<?php

class IndexController extends Zend_Controller_Action {

    public function init () {
        /* Initialize action controller here */
    }

    public function indexAction () {
        // If user has a session and is logged in he has no bussiness being here ... REDIRECT.
        if (Zend_Auth::getInstance()->hasIdentity()) {

            $session = Model_StaticFunctions::sessionContent();
            $this->_redirect('/'.$session->username.'/home');
        }
        // Set the page class >>> Important if you want the page to load properly using css.
        $this->view->headTitle('Welcome to the Scapehouse!', 'SET');
        $this->view->pageClass = "homepage public";

        if (! isset($_POST['username']) || ! isset($_POST['username']) || ! isset($_POST['username'])) {

        } else {

            $login = new Model_Login();
            // check to see if the user entered an email address.. if yes ..then use that for authentication.
            $isEmail = new Zend_Validate_EmailAddress();

            if ($isEmail->isValid($_POST['username'])) {

                $login->email = $_POST['username'];
                $login->password = $_POST['password'];
                $login->rememberMe = $_POST['rememberMe'];

                if ($login->dsLogin()) {

                    $session = Model_StaticFunctions::sessionContent();
                    $this->_redirect('/'.$session->username.'/home');

                } else {
                    $this->_redirect('login?error=true');
                }

            } else {
                // If not then use username
                $login->username = $_POST['username'];
                $login->password = $_POST['password'];
                $login->rememberMe = $_POST['rememberMe'];

                if ($login->dsLogin()) {

                    $session = Model_StaticFunctions::sessionContent();
                    $this->_redirect('/'.$session->username.'/home');

                } else {
                    $this->_redirect('login?error=true');
                }
            }

        }


    }
    public function tzcookieAction() {
        // An ajax call hits this controller to make a timezone cookie.

        $this->_helper->layout->disableLayout();
        // No view action---
        $this->_helper->viewRenderer->setNoRender(true);
        // ---
        
        $tz = $_POST['tz'];
        setcookie('tz',$tz,time()+3600*240,"/");

    }

}




