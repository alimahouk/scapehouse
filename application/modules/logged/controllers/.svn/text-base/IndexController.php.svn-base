<?php

class Logged_IndexController extends Zend_Controller_Action {

    public function init () {

    }

    public function indexAction () {

        $username = $this->_getParam("username");

        if ($username != NULL) {

            $session = Model_StaticFunctions::sessionContent();

            if ($session->username != $username) {

                $user = new Model_DbTable_Users();
                if ($user->getUserByUsername($username)) {
                    $this->_redirect("/" . $username . "/profile");
                } else {
                    $this->_redirect("/error/notfound");
                }
            } else {
                $this->_redirect("/" . $username . "/home");
            }
        }else{
            $this->_redirect("/index");
        }
    }
}