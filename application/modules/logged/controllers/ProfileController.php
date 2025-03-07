<?php

class Logged_ProfileController extends Zend_Controller_Action {

    public function init() {
        Zend_Layout::getMvcInstance()->setLayoutPath(APPLICATION_PATH . "/modules/logged/layouts");
    }

    public function indexAction() {

        $username = $this->_getParam("username");

        $userTable = new Model_DbTable_Users();
        $watchlistTable = new Logged_Model_DbTable_Watchlist();
        
        $user = $userTable->getUserByUsername($username);

        if($user["id"] == $GLOBALS["session"]->id) {
            $isPublic = false;
            $isOwner = true;
        } elseif($GLOBALS["session"]) {
            $isPublic = false;
            $isOwner = false;
        } else {
            $isPublic = true;
            $isOwner = false;
        }


        if(!$isPublic) {

            if($isOwner) {
                $this->view->headTitle("Scapehouse | My Profile", "SET");
            }else {
                $this->view->headTitle("{$user["fullname"]}'s Profile | Scapehouse", "SET");
            }

            $this->view->isWatching = $watchlistTable->isWatching($GLOBALS["session"]->id,$user["id"]);
            $this->view->isWatcher = $watchlistTable->isWatching($user["id"], $GLOBALS["session"]->id);            

            $this->view->pageClass = "profile loggedIn";

        }else {
            $this->view->pageClass = "profile public";
            $this->view->headTitle("Scapehouse | {$user["fullname"]}'s Profile", "SET");
        }

        $scapesTable = new Logged_Model_DbTable_Scape();
        $scapes = $scapesTable->getUserScapes($user["id"]);
        $scapeCount = count($scapesTable->getAllScapeidsByUser($user["id"]));
        
        if($scapes) {
            foreach ($scapes as $scape) {
                $scapesArray[] = Logged_Model_Timeline::display($scape);
            }
        }
        
        $this->view->assign($user);
        //@TODO make this more efficent
        $this->view->watcherCount = count($watchlistTable->getWatchers($user["id"]));
        $this->view->watchers = $watchlistTable->getWatchers($user["id"], 15);
        $this->view->scapes = $scapesArray;
        $this->view->scapeCount = $scapeCount;
        $this->view->isOwner = $isOwner;
        $this->view->isPublic = $isPublic;
        
        $profileTable = new Logged_Model_DbTable_Profile();
        // single quotes are needed in the string to avoid sql errors --> NEEDS FIXING
        $profileInfo = $profileTable->getProfileInfoByName($user["id"],array("'pm'","'location'","'birthday'","'sex'","'website'"));
        
        foreach($profileInfo as $profileInfo){
            
            if($decoded = json_decode($profileInfo["data"])){
                if(is_object($decoded)){
                 $profileInfo["data"] = (array) $decoded;
                }else{
                 $profileInfo["data"] = $decoded;    
                }
            }   
            $info[$profileInfo["name"]] = $profileInfo["data"];
        }
        
        $arrayMapper = new Logged_Model_Profile();
        $info = $arrayMapper->multiDimensionalarrayMap('htmlspecialchars',$info);
        $this->view->info = $info;
    }

    function editAction(){
        $this->view->pageClass = "profile loggedIn editingMod";
        $this->view->headTitle("Scapehouse | Profile editor", "SET");
        
        $username = $this->_getParam("username");
        if(strtolower($GLOBALS["session"]->username) != strtolower($username)){
            $this->_redirect("/error/notfound");
            die;
        }
        
        if($_POST){
        $profileProcessor = new Logged_Model_Profile();
        $profileProcessor->save();
        }
        
        $profileTable = new Logged_Model_DbTable_Profile();
        $profileInfo = $profileTable->getProfileInfo($GLOBALS["session"]->id);
        
        foreach($profileInfo as $profileInfo){
            
            if($decoded = json_decode($profileInfo["data"])){
                if(is_object($decoded)){
                 $profileInfo["data"] = (array) $decoded;
                }else{
                 $profileInfo["data"] = $decoded;    
                }
            }   
            $info[$profileInfo["name"]] = $profileInfo["data"];
        }
        
        $countryTable = new Logged_Model_DbTable_Countries();
        $this->view->countries = $countryTable->getAll();
        
        $arrayMapper = new Logged_Model_Profile();
        $info = $arrayMapper->multiDimensionalarrayMap('htmlspecialchars',$info);
        
        $this->view->info = $info;
        
        
        
//       print_r($_POST);
    }
    function editprofileAction() {
        // No view action---
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        // ---

        $profileProcessor = new Logged_Model_Profile();
        $profileGrandArray = $profileProcessor->process();

        $profileSingleTable = new Logged_Model_DbTable_ProfileSingle();
        $profileSingleTable->editProfile($profileGrandArray);

        $profileMultiTable = new Logged_Model_DbTable_ProfileMulti();
        $profileMultiTable->editProfile($profileGrandArray);

        $username = $_GET['username'];

        $this->_redirect("/$username/profile?edit=1");

    }


}

