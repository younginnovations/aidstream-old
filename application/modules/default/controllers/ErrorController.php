<?php

class ErrorController extends Zend_Controller_Action
{

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        var_dump($errors->exception);
                echo"testing";exit;
        switch(get_class($error->exception)) {            
            default:
                break;
        }

        // Ensure the default view suffix is used so we always return good content
        $this->_helper->viewRenderer->setViewSuffix('phtml');

        // pass the environment to the view script so we can conditionally
        // display more/less information
        $this->view->env = $this->getInvokeArg('env');

        // pass the actual exception object to the view
        $this->view->exception = $error->exception;

        // pass the request to the view
        $this->view->request   = $error->request;
        
        
        if (!$errors) {
            $this->view->message = 'You have reached the error page';
            return;
        }
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
        
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                $this->render('error404'); 
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
        
        $this->view->request   = $errors->request;
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }


    /**
     * Shows the unauthorized access action
     */
    public function unauthorizedAction() {}


    /*
     * Shows the unauthorized access action
     */
    public function error404Action() {}





}

