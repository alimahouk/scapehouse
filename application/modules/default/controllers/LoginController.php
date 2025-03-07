<?php

class LoginController extends Zend_Controller_Action
{

    public function init ()
    {
    /* Initialize action controller here */
    }

    public function indexAction ()
    {

        // If user has a session and is logged in he has no bussiness being here ... REDIRECT.
        if (Zend_Auth::getInstance()->hasIdentity()) {
            
            $sessionContent = Model_StaticFunctions::sessionContent();      
            $this->_redirect('/'.$sessionContent->username.'/home');
        }
        
        
        $login = new Model_Login();
        
        // check to see if the user entered an email address.. if yes ..then use that for authentication.
        $isEmail = new Zend_Validate_EmailAddress();
        
        if ($isEmail->isValid($_POST['username'])) {
            
            $login->email = $_POST['username'];
            $login->password = $_POST['password'];
            $login->rememberMe = $_POST['rememberMe'];
        
        if ($login->dsLogin()) {
            
            $this->_redirect($_SERVER["HTTP_REFERER"]);  
        }
        } else {
            
            $login->username = $_POST['username'];
            $login->password = $_POST['password'];
            $login->rememberMe = $_POST['rememberMe'];
        
            if ($login->dsLogin()) {
            
            if(!$_GET["redirect"]){  
            $this->_redirect($_SERVER["HTTP_REFERER"]);
            }else{
            $this->_redirect($_GET["redirect"]);   
            }
        
        }
     }
        
        // Set the page class and head title>>> Important if you want the page to load properly useing css.
        

        $this->view->headTitle('Scapehouse | Login', 'SET');
        $this->view->pageClass = "login";
    
    }
} 
 


        
         

