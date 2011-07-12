<?php

require_once 'PHPUnit/Framework/TestCase.php';

class UserControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{
    public function setUp()
    {
        $this->bootstrap = array($this, 'appBootstrap');
        parent::setUp();
    }
    
    public function appBootstrap()
    {
        $this->_application = new Zend_Application(APPLICATION_ENV,
              APPLICATION_PATH . '/configs/application.ini'
        );
        $this->_application->bootstrap();
        
        /**
         * Fix for ZF-8193
         * http://framework.zend.com/issues/browse/ZF-8193
         * Zend_Controller_Action->getInvokeArg('bootstrap') doesn't work
         * under the unit testing environment.
         */
        $front = Zend_Controller_Front::getInstance();
        if($front->getParam('bootstrap') === null) {
            $front->setParam('bootstrap', $this->_application->getBootstrap());
        }
    }
    

    public function tearDown()
    {
        /* Tear Down Routine */
    }
    
    public function testCallingControllerWithoutActionShouldPullFromIndexAction()
    {
        $this->dispatch('/user/user');
        $this->assertResponseCode(200);
        $this->assertModule('user');
        $this->assertController('user');
        $this->assertAction('login'); // note it also takes care of redirects
    }
    
    public function testInvalidLogin()
    {
        $this->request
             ->setMethod('POST')
             ->setPost(array(
                 'username' => 'foobar',
                 'password' => 'foobar'
             ));
        $this->dispatch('/user/user/login');
        $this->assertFalse(Zend_Auth::getInstance()->hasIdentity());
        $this->assertQueryContentContains('html body div#wapper div.container_12 div#contain-body div.grid_9 div#contain-left div#contain-area div#contain div#flash-messages ul.error li', 'Invalid username or password.');
        
//        $domQuery = new Zend_Dom_Query();
//        $domQuery->setDocument($this->getResponse()->getBody());
//        $result = $domQuery->queryXpath('div#wapper div.container_12 div#contain-body div.grid_9 div#contain-left div#contain-area div#contain div#flash-messages ul.error li');
//        var_dump($result);
//        var_dump(count($result));
//        Zend_Debug::dump($result->getDocument()->saveXml($result->current()));
//        Zend_Debug::dump($result->current()->nodeValue);
//        Zend_Debug::dump($result->current()->textContent);
    }
    
    public function testLoginPageFormFields()
    {
        $this->dispatch('/user/user/login');
        
        $domQuery = $this->getQuery();
        $domQuery->setDocument($this->getResponse()->getBody());
        $result = $domQuery->query(".zend_form dt label");
        
        // Note there are not normal spaces, they are result of &nbsp;
        $expectedLabels = array(
            'Username: *',
            'Password: *',
        );
        $actualLabels = array();
        foreach ($result as $element) {
//            var_dump($element->nodeValue);
//            Zend_Debug::dump(Yipl_Utils_Dom::saveXml($element));
            $actualLabels[] = $element->nodeValue;
        }
        $this->assertEquals($expectedLabels, $actualLabels);
        
//        $strForgotPasswordLink = '<a href="/user/forgotpassword">Forgot your password?</a>';
//        $this->assertXpathContentContains('//*[@id="forget-password"]', $strForgotPasswordLink);
    }
    
    public function testValidLoginShouldInitializeAuthSessionAndRedirectToProfilePage()
    {
        // For superadmin user
        $this->request
             ->setMethod('POST')
             ->setPost(array(
                 'username'    => 'superadmin',
                 'password' => 'admin'
             ));
        $this->dispatch('/user/user/login');
        $this->assertTrue(Zend_Auth::getInstance()->hasIdentity());
        $this->assertRedirectTo('/admin/dashboard');
        
        // For normal user
        $this->request
             ->setMethod('POST')
             ->setPost(array(
                 'username'    => 'unicef_admin',
                 'password' => 'admin'
             ));
        $this->dispatch('/user/user/login');
        $this->assertTrue(Zend_Auth::getInstance()->hasIdentity());
        $this->assertRedirectTo('/wep/dashboard');
        
        // For affiliate user
        /*$this->request
             ->setMethod('POST')
             ->setPost(array(
                 'email'    => 'affiliate@yipl.com.np',
                 'password' => 'admin'
             ));
        $this->dispatch('/user/login');
        $this->assertTrue(Zend_Auth::getInstance()->hasIdentity());
        $this->assertRedirectTo('/affiliatepractice/dashboard');*/
    }
    
}

?>