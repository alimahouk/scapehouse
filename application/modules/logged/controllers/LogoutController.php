<?php

class Logged_LogoutController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */

        // TEMPORARY
        $session = Model_StaticFunctions::sessionContent();
             if(!$session){
               $this->_redirect("/login");
               exit;
             }
    }

    public function indexAction()
    { // When called, deletes and destroys the session.

        Zend_Session::destroy(true,true);
        $this->_redirect('index');
        exit;
    }


}
