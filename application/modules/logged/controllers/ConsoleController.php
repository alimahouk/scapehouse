<?php

class Logged_ConsoleController extends Zend_Controller_Action{
    
  public function init()
    {
       Zend_Layout::getMvcInstance()->setLayoutPath(APPLICATION_PATH . '/modules/logged/layouts');

       // TEMPORARY
        $session = Model_StaticFunctions::sessionContent();
             if(!$session){
               $this->_redirect("/login");
             }
    }

    public function indexAction()
    {
        $this->view->headTitle('Scapehouse | Settings', 'SET');
        $this->view->pageClass = "controlConsole loggedIn";
    }

    public function accountAction()
    { 
        $this->view->headTitle('Scapehouse | Account ', 'SET');
        $this->view->pageClass = "controlConsole loggedIn account";
        
        $signupProcessor = new Model_Signup();
    
        if($_POST["firstname"] || $_POST["lastname"]){
          $signupProcessor->validateName();
          
        unset($_POST);
        
        if($signupProcessor->signupErrors){
         $this->_redirect("/{$GLOBALS["session"]->username}/console/account?nameError=true");
        }else{
          $usersTable = new Model_DbTable_Users();
          $usersTable->updateName($GLOBALS["session"]->id,$signupProcessor->firstname,$signupProcessor->lastname);
          
          $GLOBALS["session"]->firstname = $signupProcessor->firstname;
          $GLOBALS["session"]->lastname = $signupProcessor->lastname;
          $GLOBALS["session"]->fullname = $signupProcessor->firstname." ".$signupProcessor->lastname;
          
          $this->_redirect("/{$GLOBALS["session"]->username}/console/account?jelloon=affirm&jelloonContent=Changes saved.");
        }
     }
     
     if($_POST["newPassword"] || $_POST["oldPassword"]){
        $usersTable = new Model_DbTable_Users();
        
        $lengthCheck = new Zend_Validate_StringLength();
        $lengthCheck->setMin(6);
        
        if($lengthCheck->isValid($_POST["newPassword"])) {
          if($usersTable->checkPassword($GLOBALS["session"]->id,Model_StaticFunctions::salted_sha1($_POST["oldPassword"]))){
              $usersTable->updatePassword($GLOBALS["session"]->id,Model_StaticFunctions::salted_sha1($_POST["newPassword"]));
              $this->_redirect("/{$GLOBALS["session"]->username}/console/account?jelloon=affirm&jelloonContent=Changes saved.#password");
          }
        }else{
          $this->_redirect("/{$GLOBALS["session"]->username}/console/account?passwordError=true#password");
        }

     }
    }

    public function statsAction()
    { 
        $this->view->headTitle('Scapehouse | Stats', 'SET');
        $this->view->pageClass = "controlConsole loggedIn stats";
    }

    public function privacyAction()
    {
       
        $this->view->headTitle('Scapehouse | Privacy', 'SET');
        $this->view->pageClass = "controlConsole loggedIn privacy";

        $genInfo = $_POST["genInfo"];
        $conInfo = $_POST["conInfo"];
        $perInfo = $_POST["perInfo"];
        $eduInfo = $_POST["eduInfo"];
        $workInfo = $_POST["workInfo"];
        $gallery = $_POST["gallery"];
        $scape = $_POST["scape"];
        $maxPrivacy = $_POST["maxPrivacy"];


        if($_POST){
        $privacyTable = new Logged_Model_DbTable_PrivacyGlobal();
        $privacyTable->save($genInfo,$conInfo,$perInfo,$eduInfo,$workInfo,$gallery,$scape,$maxPrivacy);
        }
    }

    public function notifsAction()
    {  
        $this->view->headTitle('Scapehouse | Notifications Control', 'SET');
        $this->view->pageClass = "controlConsole loggedIn notifs";
    }
    public function aboutAction()
    {
        $this->view->headTitle('Scapehouse | About', 'SET');
        $this->view->pageClass = "controlConsole loggedIn about";
    }
    
    public function personalizeAction(){
      
        $this->view->headTitle('Scapehouse | Personalization', 'SET');
        $this->view->pageClass = "controlConsole loggedIn personalization";
        
        if($_GET["theme"]){
        $settingTable = new Logged_Model_DbTable_Setting();
        $settingTable->saveTheme($GLOBALS["session"]->id,$_GET["theme"]);
        }
        
    }


    
}

?>
