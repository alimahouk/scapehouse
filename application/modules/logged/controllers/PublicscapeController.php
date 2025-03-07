<?php

class Logged_PublicscapeController extends Zend_Controller_Action
{

    public function init()
    {
        // TEMPORARY
        $session = Model_StaticFunctions::sessionContent();
             if(!$session){
               $this->_redirect("/login");
             }
    }

    public function indexAction()
    {
        // action body
    }


}

