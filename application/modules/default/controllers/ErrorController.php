<?php
/**
 * Controller to handle error
 * @author YIPL dev team
 */
class ErrorController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->Layout->setLayout('layout_error');
        
        // diable all menu items
        $this->view->blockManager()->disable('partial/superadmin-menu.phtml');
        $this->view->blockManager()->disable('partial/dashboard.phtml');
        $this->view->blockManager()->disable('partial/primarymenu.phtml');
        $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->disable('partial/published-list.phtml');
        $this->view->blockManager()->disable('partial/published-list.phtml');
        $this->view->blockManager()->disable('partial/usermgmtmenu.phtml');
    }
    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
        if(!$errors){
            $this->view->message = 'You have reached the error page';
        } else {
            $excpts = Zend_Registry::getInstance()
                ->config
                ->resources
                ->frontController
                ->params
                ->displayExceptions;
            if($excpts){
                $this->view->exception = $errors->exception;
            }
            $this->view->request   = $errors->request;
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
                $log->log($errors->exception->getMessage() , Zend_Log::ERR);
            }
        }
        
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Logger')) {
            return false;
        }
        $log = $bootstrap->getResource('Logger');
        return $log;
    }


    /**
     * Shows the unauthorized access action
     */
    public function unauthorizedAction() {}


    /*
     * Shows the 404 error action
     */
    public function error404Action() {}
}

