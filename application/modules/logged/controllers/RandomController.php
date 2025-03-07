<?php

class Logged_RandomController extends Zend_Controller_Action
{

    public function init()
    {
        Zend_Layout::getMvcInstance()->setLayoutPath(APPLICATION_PATH . "/modules/logged/layouts");
    }

    public function indexAction()
    {
        echo "Hello World";
        $this->view->headTitle("Scapehouse | Home", "SET");
        $this->view->pageClass = "random loggedIn";
        
    }


}
