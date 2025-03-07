<?php

class Logged_WatchlistController extends Zend_Controller_Action {

    public function init() {
        Zend_Layout::getMvcInstance()->setLayoutPath(APPLICATION_PATH . '/modules/logged/layouts');

        // TEMPORARY
        $session = Model_StaticFunctions::sessionContent();
             if(!$session){
               $this->_redirect("/login");
             }
    }

    public function indexAction() {
        $this->_redirect("/index");
        $username = $this->_getParam("username");

        $session = Model_StaticFunctions::sessionContent();
        $userTable = new Model_DbTable_Users();
        $user = $userTable->getUserByUsername($username);
        $this->view->user = $user;
        
        if($session->id == $user['id']) {
            $this->view->headTitle('Scapehouse | My Watchlist', 'SET');
        }else {
            $this->view->headTitle("Scapehouse | {$user['fullname']}'s Watchlist", 'SET');
        }
        $this->view->pageClass = "watchlist loggedIn watching";
    }

    public function watchersAction() {

        $username = $this->_getParam("username");

        $session = Model_StaticFunctions::sessionContent();
        $userTable = new Model_DbTable_Users();
        $user = $userTable->getUserByUsername($username);
        $this->view->user = $user;

        if($session->id == $user['id']) {
            $this->view->headTitle('Scapehouse | My Watchlist', 'SET');
        }else {
            $this->view->headTitle("Scapehouse | {$user['fullname']}'s Watchlist", 'SET');
        }

        $this->view->pageClass = "watchlist loggedIn watchers";
    }

    public function watchingAction() {

        $username = $this->_getParam("username");

        $session = Model_StaticFunctions::sessionContent();
        $userTable = new Model_DbTable_Users();
        $user = $userTable->getUserByUsername($username);
        $this->view->user = $user;

        if($session->id == $user['id']) {
            $this->view->headTitle('Scapehouse | My Watchlist', 'SET');
        }else {
            $this->view->headTitle("Scapehouse | {$user['fullname']}'s Watchlist", 'SET');
        }

        $this->view->pageClass = "watchlist loggedIn watching";
    }


}

