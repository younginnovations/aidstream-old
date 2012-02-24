<?php 
class App_AccessCheck extends Zend_Controller_Plugin_Abstract
{
    private $_acl = null;
    //private $_auth = null;
    
    public function __construct(Zend_Acl $acl)
    {
        $this->_acl = $acl;
      //  $this->_auth = $auth;
    }
    
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $module = $request->getModuleName();
        $resource = $request->getControllerName();
        $action = $request->getActionName();

//getting the role from the registry and defining some role for the null character as guest makes authentication more flexible
//if the roles is not allowed to use the resource then the user is redirected to action login of controller user
        
        if(!$this->_acl->isAllowed(Zend_Registry::get('role'),$module.':'.$resource,$action))
        {
            $request->setModuleName('user')
                    ->setControllerName('user')
                    ->setActionName('login');
        }

        //$this->_acl->setDynamicPermisssion();
        
    }
//    public function postDispatch()
//    {
//        $response = $this->getResponse();
//    }

}