<?php

class User_UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function registerAction()
    {
        $form = new User_Form_User_RegisterForm();
        $this->view->form = $form;
        $formData = $this->getRequest()->getPost();
        if ($this->getRequest()->isPost()) {

            if ($form->isValid($formData)) {

                $userCheck = new User_Model_DbTable_User();
                $username = $form->getValue('username');
                $email = $form->getValue('email');
                $mobile = $form->getValue('mobile');
                $result = $userCheck->checkUnique($email);
                if ($result == TRUE) {
                    $password = $form->getValue('password');
                    $data = array();
                    $data['user_name'] = $form->getValue('username');
                    $data['email'] = $form->getValue('email');
                    //                    $data['mobile'] = $form->getValue('mobile');
                    $data['password'] = md5($password);
                    // role id has been set to 2 so that it could take user as role from table role this needs to be changed as needed
                    $data['role_id'] = 2;
                    $user = new User_Model_DbTable_User();
                    $user_id = $user->insert($data);
                    $this->_redirect('user/user/login');
                } else {
                    $this->_helper->FlashMessenger->addMessage(array('error' => 'User already exist. Please enter different email address or username'));
                }
            }//end of if
        }
        $this->view->placeholder('title')->set('Sign Up');
    }

    /**
     * while clicking ther forgotpassword
     * a mail is send to user with a link to reset the new password
     */
    public function forgotpasswordAction()
    {
        $form = new User_Form_User_Forgotpassword();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $email = $form->getValue('email');

                $unique = new User_Model_DbTable_User();
                $result = $unique->checkUnique($email);

                if ($result == FALSE) {
                    try {
                        $val = $this->sendemail($email);
                        $resetValue = $val;
                        $reset = new User_Model_DbTable_Reset();
                        $reset->insert(array('email' => $email, 'value' => $resetValue, 'reset_flag' => '0'));
                        $this->_helper->FlashMessenger->addMessage(array('message' => 'Further instructions have been sent to your e-mail address.'));
                        $this->_redirect('code-list/code-list-index/langid/1');
                    } catch (Exception $e) {

                        $this->_helper->FlashMessenger->addMessage(array('error' => 'Error in sending mail.'));
                    }//end of try catch
                } else {

                    $this->_helper->FlashMessenger->addMessage(array('error' => 'Sorry, ' . $email . ' is not recognized as a valid e-mail address.'));
                }//end of if
            } else {
                $form->populate($formData);
            }
        }
        $this->view->placeholder('title')->set('Request New Password');
    }

    public function loginAction()
    {
        
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $identity = Zend_Auth::getInstance()->getIdentity();
//            print_r($identity->roles);exit;
            if($identity->role == 'superadmin' ){
                $this->_redirect('admin/dashboard');
            }elseif($identity->role == 'admin'){
                $this->_redirect('wep/dashboard');
            }
            
        }

        $request = $this->getRequest();
        $form = new User_Form_User_Login();
        if ($request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {

                $authAdapter = $this->getAuthAdapter();
                $username = $form->getValue('username');
                $password = $form->getValue('password');

                $model = new User_Model_DbTable_User();
                
                $authAdapter->setIdentity($username)
                ->setCredential($password);


                //@todo before session make sure the status of the user is 1

                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($authAdapter);

                if ($result->isvalid()) {
                    
                    $status = $model->getUserByUsername($username);
                    //print_r($status);exit();
                    if($status['status'] != 1){
//                        print "dddsf";exit;
                        $this->_helper->FlashMessenger->addMessage(array('error' => 'Your registration has not been confirmed.'));
                        $this->_redirect('user/user/logout');
                    }
                    $identity = $authAdapter->getResultRowObject();

                    //getting role from table role and merging it with $authAdapter->getResultRowObject() [adding role to identity]
                    $rolevalue = new User_Model_DbTable_Role;
                    $role = $rolevalue->getRoleById($identity->role_id);
                    $obj2 = new stdClass;
                    $obj2->role = $role['role'];

                    //@todo check the role an redirect to the proper dashboard
                    $identity = (object) array_merge((array) $identity, (array) $obj2);
                    $authStorage = $auth->getStorage();
                    $authStorage->write($identity);

                    $this->_helper->FlashMessenger->addMessage(array('message' => 'Successfully Logged In'));
                    if($identity->role == 'superadmin' ){
                        $this->_redirect('admin/dashboard');
                    }elseif($identity->role == 'admin'){
                        $this->_redirect('wep/dashboard');
                    }                }
                else
//                print "dd";exit;
                $this->_helper->FlashMessenger->addMessage(array('error' => 'Invalid username or password.'));
            }
        }
        $this->view->form = $form;
    }

    /**
     * view the user account
     * @return unknown_type
     */
    public function myaccountAction()
    {
        $auth = Zend_Auth::getInstance()->getIdentity();
        $user_id = $auth->user_id;

        $userModel = new User_Model_DbTable_User();
        $row = $userModel->getUserById($user_id);
        $this->view->row = $row;
        $this->_helper->layout()->setLayout('layout');
    }

    public function editAction()
    {
        $user_id = $this->getRequest()->getParam('user_id');
        $model = new User_Model_DbTable_User();
        $row = $model->getUserById($user_id);
        $form = new User_Form_User_Edit();
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {


                $username = $form->getValue('username');
                $email = $form->getValue('email');
                $mobile = $form->getValue('mobile');
                $data = array();
                $data['user_name'] = $username;
                $data['email'] = $email;
                $data['mobile'] = $mobile;
                $user_id = $model->editUser($data, $row->user_id);

                $useId['user_id'] = $user_id;

                $this->_redirect('user/user/myaccount/user_id/' . $row->user_id);
            }//end of inner if
        } else {
            $form->populate($row->toArray());
        }
        $this->view->placeholder('title')->set('Edit account');
        $this->_helper->layout()->setLayout('layout');
    }

    /**
     *
     * @return unknown_type
     */
    public function resetpasswordAction()
    {
        $resetValue = $this->getRequest()->getParam('value');
        $resetEmail = $this->getRequest()->getParam('email');

        $userModel = new User_Model_DbTable_User();
        $reset = new User_Model_DbTable_Reset();
        $resetResult = $reset->uniqueValue($resetEmail, $resetValue);
        $resetId = $reset->getResetId($resetEmail, $resetValue);
        if ($resetResult == FALSE) {
            $this->_helper->FlashMessenger->addMessage(array('error' => 'You have already used this one-time login link.'));
            $this->_redirect('user/user/login');
        } else {

            $form = new User_Form_User_Resetpassword();
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                $formData = $this->getRequest()->getPost();
                if ($form->isValid($formData)) {
                    $email = $form->getValue('email');
                    $password = $form->getValue('password');

                    //update the password in user table
                    $data['password'] = md5($password);
                    $userModel->update($data, array('email = ?' => $resetEmail));
                    //update the reset value in reset table
                    $resetData['reset_flag'] = 1;
                    $reset->update($resetData, array('reset_id' => $resetId));
                    $this->_redirect('user/user/login');
                }
            } else {
                $form->populate(array('email' => $resetEmail));
            }
        }//end of outer if
        $this->view->placeholder('title')->set('Reset Password');
        $this->_helper->layout()->setLayout('layout');

    }

    //end of else

    public function changepasswordAction()
    {
        $user_id = $this->getRequest()->getParam('user_id');

        if (!$user_id) {
            throw new Exception('Invalid Request');
        }
        $auth = Zend_Auth::getInstance()->getIdentity();

        if ($user_id != $auth->user_id) {
            throw new App_Exception_AccessDenied('Access Denied');
        }

        $model = new User_Model_DbTable_User();
        $user = $model->getUserById($user_id);

        $form = new User_Form_User_Changepassword();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formdata = $this->getRequest()->getPost();

            if (!$form->isValid($formdata)) {
                $form->populate($formdata);
            } else {
                try {

                    $oldPassword = $this->getRequest()->getParam('oldpassword');

                    if (md5($oldPassword) == $user->password) {

                        $data['password'] = $this->getRequest()->getParam('password');
                        $model->changePassword($data, $user_id);


                        $this->_helper->FlashMessenger->addMessage(array('message' => 'Changed password successfully.'));

                        $this->_redirect('user/user/login');
                    } else {

                        $this->_helper->FlashMessenger->addMessage(array('error' => 'Old password did not match.'));
                    }
                } catch (Exception $e) {
                    print 'Error Occured';
                    print $e->getMessage();
                }//end of try catch
            }
        }
        $this->view->user = $user;
        $this->_helper->layout()->setLayout('layout');
    }

    public function logoutAction()
    {
        $auth = Zend_Auth::getInstance()->getIdentity();
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->FlashMessenger->addMessage(array('message' => 'Successfully logged out.'));
                   
        $this->_redirect('user/user/login');
    }

    private function getAuthAdapter()
    {
        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        $authAdapter->setTableName('user')
        ->setIdentityColumn('user_name')
        ->setCredentialColumn('password')
        ->setCredentialTreatment('md5(?)');

        return $authAdapter;
    }

    private function sendemail($toEmail)
    {
        $emailSetting = new App_Email();
        $setting = $emailSetting->setting();

        $fromEmail = "support@iatistandard.org";
        $replace = "Thank you for registering at !site_name!
                    You may also log in by clicking on this link or copying and pasting it in your browser:
                    !view_url!
                    ------
                    !site_name!";
        $siteName = "IATI Standard";

        $url = "http://" . $_SERVER['HTTP_HOST'] . $this->view->baseUrl() . '/user/user/resetpassword';

        $uniqueId = md5(uniqid());

        $resetSite = "http://" . $_SERVER['HTTP_HOST'] . $this->view->baseUrl() . '/user/user/resetpassword/email/' . $toEmail . '/value/' . $uniqueId;

        $bodyTemp1 = str_replace('!reset_site', $resetSite, $replace);

        $bodyTemp = str_replace('!view_url!', $url, $bodyTemp1);

        $body = str_replace('!site_name!', $siteName, $bodyTemp);

        $subject = 'Replacement login information for ' . $toEmail;

        $mail = new Zend_Mail();

        $mail->setBodyText($body)
        ->setFrom($fromEmail)
        ->addTo($toEmail)
        ->setSubject($subject);

        $result = $mail->send();

        return $uniqueId;
    }

    public function preDispatch()
    {
        $this->_helper->layout()->setLayout('login_page');
    }

    public function testAction(){
        
    }
}

