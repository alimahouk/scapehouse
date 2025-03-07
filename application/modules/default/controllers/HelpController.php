<?php

class HelpController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */

        Zend_Layout::getMvcInstance()->setLayoutPath(APPLICATION_PATH . '/modules/default/layouts/corprate');
    }

    public function indexAction() {
        $this->view->headTitle('Scapehouse Help Center', 'SET');
        $this->view->pageClass = "help main";
    }
    public function consoleAction() {
        $this->view->headTitle('Scapehouse Help Center | Control console', 'SET');
        $this->view->pageClass = "help console";
    }
    public function photosAction() {
        $this->view->headTitle('Scapehouse Help Center | Photos', 'SET');
        $this->view->pageClass = "help photos";
    }
    public function scapesAction() {
        $this->view->headTitle('Scapehouse Help Center | Scapes', 'SET');
        $this->view->pageClass = "help scapes";
    }
    public function privacyAction() {
        $this->view->headTitle('Scapehouse Help Center | Privacy', 'SET');
        $this->view->pageClass = "help privacy";
    }
    public function emosAction() {

        $this->view->headTitle('Scapehouse Help Center | Emoticon guide', 'SET');
        $this->view->pageClass = "help scapes emoticons";
    }
    public function profilesAction() {

        $this->view->headTitle('Scapehouse Help Center | Profiles', 'SET');
        $this->view->pageClass = "help profiles";
    }
    
    public function watchlistAction() {

        $this->view->headTitle('Scapehouse Help Center | Watchlist', 'SET');
        $this->view->pageClass = "help watchlist";
    }
    
    public function noteboardAction() {

        $this->view->headTitle('Scapehouse Help Center | Noteboard', 'SET');
        $this->view->pageClass = "help noteboard";
    }
    
    public function faqsAction() {
        $this->view->headTitle('Scapehouse Help Center | FAQs', 'SET');
        $this->view->pageClass = "help faqs";
    }
    
    public function issuesAction() {
        $this->view->headTitle('Scapehouse Help Center | Known issues', 'SET');
        $this->view->pageClass = "help issues";
    }

}

