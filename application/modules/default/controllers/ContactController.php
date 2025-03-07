<?php

class ContactController extends Zend_Controller_Action
{

    public function init ()
    {
    /* Initialize action controller here */
        Zend_Layout::getMvcInstance()->setLayoutPath(APPLICATION_PATH . '/modules/default/layouts/corprate');
    }

    public function indexAction ()
    {
        $this->view->headTitle('Scapehouse | Contact', 'SET');
        $this->view->pageClass = "corporate contact";

    }
}
