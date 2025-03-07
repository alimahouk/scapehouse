<?php

class ErrorController extends Zend_Controller_Action
{

    public function init() {
        /* Initialize action controller here */

        Zend_Layout::getMvcInstance()->setLayoutPath(APPLICATION_PATH . '/modules/logged/layouts/');
    }

    public function errorAction()
    {
        
        $errors = $this->_getParam('error_handler');
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
        
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                break;
        }
        
        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->crit($this->view->message, $errors->exception);
        }
        
        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }
        
        $this->view->request = $errors->request;
// Custom ----
        if($errors->exception->getMessage() == "Mysqli statement execute error : No data supplied for parameters in prepared statement"){
             $this->view->pageClass = "utilPage loggedIn pageNotFound";
             $this->view->headTitle('Scapehouse | Page not found!', 'SET');
             $this->view->notFound = true;
        }elseif($this->getResponse()->getHttpResponseCode() == "404" ){
            $this->view->pageClass = "utilPage loggedIn pageNotFound";
            $this->view->headTitle('Scapehouse | Page not found!', 'SET');
            $this->view->notFound = true;
        }
        else{
             $this->view->pageClass = "applicationError loggedIn";
             $this->view->headTitle('Scapehouse | Error!', 'SET');
        }
        
        if(!$GLOBALS["session"]){
            $this->view->pageClass = "utilPage public pageNotFound";
            $this->view->isPublic = true;
        }
 // Custom ----

    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasPluginResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }


}

