<?php

class Logged_NotifsController extends Zend_Controller_Action {

    public function init() {
        Zend_Layout::getMvcInstance()->setLayoutPath(APPLICATION_PATH . '/modules/logged/layouts');

        $session = Model_StaticFunctions::sessionContent();
        if(!$session) {
            $this->_redirect("/login");
            exit;
        }
    }

    public function indexAction() {
        $this->view->headTitle('Scapehouse |  Notifications', 'SET');
        $this->view->pageClass = "notifications loggedIn";

        $notifTable = new Logged_Model_DbTable_Notif();
        $this->view->notifs = $notifTable->getNotifs();

    }


}

