<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public $_acl;

    protected function _initModuleAutoLoad()
    {
        $moduleLoader = new Zend_Application_Module_Autoloader(
                        array(
                            "namespace" => '',
                            "basePath" => APPLICATION_PATH . '/modules/default'));                
    }
    
    protected function _initRegisterNamespace()
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('Iati_');
        $autoloader->registerNamespace('App_');
        $autoloader->registerNamespace('Ckan_');
    }
    
    protected function _initRegistry()
    {
        $registry = Zend_Registry::getInstance();
        $registry->config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV, true);

        return $registry;
    }
    
    protected function _initSession()
    {
        $sessionOptions = Zend_Registry::get('config')->resources->session->toArray();
        Zend_Session::setOptions($sessionOptions);
    }

    function _initFrontController()
    {
        // The Zend_Front_Controller class implements the Singleton pattern
        $frontController = Zend_Controller_Front::getInstance();

        // look in the modules directory and automatically make modules out of all folders found
        $frontController->addModuleDirectory(APPLICATION_PATH . '/modules');

        // forces the front controller to forward all errors to the default error controller (may already be false by default)
        $frontController->throwExceptions(false);

        return $frontController;
    }

    function _initViewHelpers()
    {
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();

        $view->doctype('HTML4_STRICT');
        $view->headMeta()->appendHttpEquiv('Content-type', 'text/html; charset=utf-8');
        $view->headTitle('ZendFramework');
        // Initialize Zendx jquery viewHelper
        $view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
    }

    /* public function _initRouter()
      {
      $routes = new Zend_Config_Xml(APPLICATION_PATH . '/configs/routes.xml');
      $router = Zend_Controller_Front::getInstance()->getRouter();
      $router->addConfig($routes, 'routes');
      } */

    function _initRouting()
    {
        $router = $this->getResource('frontController')->getRouter();
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routing.ini', 'routing');
        $router->addConfig($config, 'routes');
    }

    /* public function _initFolderPath()
      {
      $pathConfig = $this->getOptions();

      $path = $pathConfig['folder']['path'];

      //        $path
      } */

    /* public function _initSetLibraryPaths()
      {
      $config = $this->getOptions();
      $schemaPath = $config['iati']['validator']['schemaPath'];

      if ($schemaPath) {
      Iati_Iatischema_Validator::setSchemaPath($schemaPath);
      }
      } */

    protected function _initAcl()
    {
        $this->_acl = new App_Acl();
        //$auth = Zend_Auth::getInstance();
        $frontController = Zend_Controller_Front::getInstance();
        $frontController->registerPlugin(new App_AccessCheck($this->_acl));
        
        //for the guest user role is assigned as guest rather than null and for other roles it is fetched from zend_auth
        if (Zend_Auth::getInstance()->hasIdentity()) {
            Zend_Registry::set('role', Zend_Auth::getInstance()->getStorage()->read()->role);
        } else {
            Zend_Registry::set('role', 'guest');
        }

        // writing the Zend_Acl to registry allowing to use it any where in the model

        Zend_Registry::set('acl', $this->_acl);
    }

    protected function _initLogger()
    {
        $config = $this->getOption('email');
        $logger = new Zend_Log();
        //$logger->addPriority('SQL');
        // TODO set filter for production env--supress logging under warning level
        $writer_filesys = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../data/log/zf.iati.log');
        $logger->addWriter($writer_filesys);

        if (APPLICATION_ENV == 'production') {
            $email = new Zend_Mail();

            $email->setFrom($config['fromAddress'])->addTo($config['errLogging']);

            $writer_email = new Zend_Log_Writer_Mail($email);
            $writer_email->setSubjectPrependText('Urgent: IATI Server Error!');

            // only email warning level "errors" or higher
            $writer_email->addFilter(Zend_Log::WARN);
            $logger->addWriter($writer_email);
        }

        Zend_Registry::set('logger', $logger);

        return $logger;
    }

    function _initappEmail()
    {
        $registry = Zend_Registry::getInstance();
        $registry->mailer = new App_Email();
        return $registry;
    }

}
