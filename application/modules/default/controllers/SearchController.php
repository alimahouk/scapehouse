<?php

class SearchController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        Zend_Layout::getMvcInstance()->setLayoutPath(APPLICATION_PATH . '/modules/logged/layouts/');
    }

    public function indexAction() {


        $searchQuery = trim((strip_tags($_GET["searchBox"])));
        $searchType = $_GET["searchType"];
        $this->view->page = $_GET["page"];
        $page = $_GET["page"] - 1;

        if(!$page || $page < 0) {
            $page = 0;
        }

        $session = Model_StaticFunctions::sessionContent();
        if($session) {
            $status = "loggedIn";
        }else {
            $status = "public";
        }

        if($searchType == "scapes" && $searchQuery) {

            $indexType = "scape";


        }elseif($searchType == "people" && $searchQuery) {

            $indexType = "users";

        }else {
            $this->view->hits = NULL;
            $this->view->headTitle("Scapehouse | Search", "SET");
            $this->view->pageClass = "searchResults none public";
            $this->view->isPublic = true;
        }

        if($indexType) {

            $offset = $page * 15;
            if($indexType == "scape") {
                $scapeTable = new Logged_Model_DbTable_Scape();
                $this->view->hits = $scapeTable->search($searchQuery,$offset);
            }else if($indexType == "users"){
              //  $validEmail = new Zend_Validate_EmailAddress();
              //  if($validEmail->isValid($searchQuery))
                $usersTable = new Model_DbTable_Users();
                $this->view->hits = $usersTable->searchUsersByName($searchQuery,15,$offset,false);
            }

            $this->view->headTitle("Scapehouse | Search", "SET");
            $this->view->pageClass = "searchResults $searchType $status";
            if(!$session){
            $this->view->isPublic = true;
            }
        }

    }

}
