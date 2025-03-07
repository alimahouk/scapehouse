<?php

class Logged_ScapeController extends Zend_Controller_Action {

    public function init() {
        Zend_Layout::getMvcInstance()->setLayoutPath(APPLICATION_PATH . "/modules/logged/layouts");
    }

    public function indexAction() {      
    
        $scapeid = $this->_getParam("id");
        $urlUsername = $this->_getParam("username");
        
        $scapeTable = new Logged_Model_DbTable_Scape();
        $scape = $scapeTable->getScapebyId($scapeid);
        
        if($scape["userid"] == $GLOBALS["session"]->id) {
            $isPublic = false;
            $isOwner = true;
        } elseif($GLOBALS["session"]) {
            $isPublic = false;
            $isOwner = false;
        } else {
            $isPublic = true;
            $isOwner = false;
        }
        
        if($isPublic){
            $this->view->pageClass = "discussion public";
            $this->view->loginJs = "$('#sidebarLoginTooltip').show();$('#loginUsername').focus();return false;";
        }else{
            $this->view->pageClass = "discussion loggedIn";
        }
        
        $userTable = new Model_DbTable_Users();
        $scapeOwner = $userTable->getUserById($scape["userid"]);
        $scape["fullname"] = $scapeOwner["fullname"];
        $scape["username"] = $scapeOwner["username"];
        
        if(strtolower($scapeOwner["username"]) != strtolower($urlUsername)){ // Redirect if username is not == to the one in the database
         $this->_redirect("/error/notfound");
         die;
        }  
        //Push vars to view
        $scapeTitleRaw = $scape["title"];
        $scape['title'] = htmlspecialchars($scape['title']); // Security against EVIL!
        $this->view->assign($scape);
        $this->view->isOwner = $isOwner;
        $this->view->isPublic = $isPublic;
        
        if ($scape['title']) {
            $this->view->headTitle("Scapehouse | {$scapeTitleRaw}", "SET");
        } else {
            $this->view->headTitle("Scapehouse | Scape by {$scapeOwner["fullname"]}", "SET");
        }
        
    }

}

