<?php
/**
 * User Controller to render pages for user module
 * Enter description here ...
 * @author geshan
 *
 */
class User_UserController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout()->setLayout('layout_wep');
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
                $data = array();
                $data['email'] = $formData['email'];
                $data['first_name'] = $formData['first_name'];
                $data['middle_name'] = $formData['middle_name'];
                $data['last_name'] = $formData['last_name'];
                $data['password'] = $formData['password'];
                $data['org_name'] = $formData['org_name'];
                $data['org_address'] = $formData['org_address'];
                $data['api_key'] = $formData['api_key'];
                $data['publisher_id'] = $formData['publisher_id'];      
                    
                $userRegister = new User_Model_DbTable_UserRegister();
                $requId = $userRegister->saveRegisterInfo($data);
                
                $mail['subject'] = 'User registration request received';                

                $mail['message'] = "The following user registered for aidstream:\n";
                $mail['message'] .=  "\nOrganisation Name: ".$data['org_name'];
                $mail['message'] .=  "\nOrganisation Address: ".$data['org_address'];
                $mail['message'] .=  "\nFirst Name: ".$data['first_name'];
                $mail['message'] .=  "\nMiddle Name: ".$data['middle_name'];
                $mail['message'] .=  "\nLast Name: ".$data['last_name'];
                $mail['message'] .=  "\nEmail: ".$data['email'];
                $mail['message'] .=  "\nPassword: ".$data['password'];
                $mail['message'] .=  "\nPublisher Id: ".$data['publisher_id'];
                $mail['message'] .=  "\nAPI Key: ".$data['api_key'];
                
                $modelMail = new Model_Mail();
                $modelMail->sendMail($mail);
                
                $this->_helper->FlashMessenger->addMessage(array('message' => 'Your registration request has been received.'));
                $this->_redirect('/');
                
            }//end of if
        }
        $this->view->placeholder('title')->set('Register user');
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
                        $this->_redirect('/');
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
    }

    public function loginAction()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $identity = Zend_Auth::getInstance()->getIdentity();
            if ($identity->role == 'superadmin') {
                $this->_redirect('admin/dashboard');
            } elseif ($identity->role == 'admin') {
                $this->_redirect('wep/dashboard');
            }
            elseif ($identity->role == "user") {
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
                    if ($status['status'] != 1) {
                        $this->_helper->FlashMessenger->addMessage(array('error' => 'Your registration has not been confirmed.'));
                        $this->_redirect('user/user/logout');
                    }
                    $identity = $authAdapter->getResultRowObject();

                    //getting role from table role and merging it with $authAdapter->getResultRowObject() [adding role to identity]
                    $rolevalue = new User_Model_DbTable_Role;
                    $role = $rolevalue->getRoleById($identity->role_id);
                    $obj2 = new stdClass;
                    $obj2->role = $role['role'];

                    $identity = (object) array_merge((array) $identity, (array) $obj2);
                    $authStorage = $auth->getStorage();
                    $authStorage->write($identity);

                    $this->_helper->FlashMessenger->addMessage(array('message' => 'Successfully Logged In'));
                    if ($identity->role == 'superadmin') {
                        $this->_redirect('admin/dashboard');
                    } elseif ($identity->role == 'admin') {
                        $this->_redirect('wep/dashboard');
                    }elseif ($identity->role == 'user'){
                        $this->_redirect('wep/dashboard');
                    }
                
                }
                else
                    $this->_helper->FlashMessenger->addMessage(array('error' => 'Invalid username or password.'));
                    $this->_redirect('/');
            } else {
                $this->_helper->FlashMessenger->addMessage(array('error' => 'Invalid data provided'));
                $this->_redirect('/');
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
        $profileModel = new User_Model_DbTable_Profile();
        $row1 = $profileModel->getProfileByUserId($user_id);
        $this->view->profile = $row1;
        $this->view->row = $row;
        $identity  = Zend_Auth::getInstance()->getIdentity();
        $this->_helper->layout()->setLayout('layout_wep');
        if($identity->role == 'user'){
            $model = new Model_Wep();
            $userPermission = $model->getUserPermission($identity->user_id);
            $permission = $userPermission->hasPermission(Iati_WEP_PermissionConts::VIEW_ACTIVITIES);
            if($permission == '0'){
                $this->view->blockManager()->disable('partial/primarymenu.phtml');
            }
            $permission = $userPermission->hasPermission(Iati_WEP_PermissionConts::ADD_ACTIVITY);
            if($permission == '0'){
                $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
            }
        }
        $this->view->blockManager()->enable('partial/dashboard.phtml');
        if($identity->role != 'superadmin'){
            $this->view->blockManager()->enable('partial/primarymenu.phtml');
            $this->view->blockManager()->enable('partial/add-activity-menu.phtml');
            $this->view->blockManager()->enable('partial/usermgmtmenu.phtml');
        }
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
                $user_id = $model->editUser($data, $row->user_id);

                $useId['user_id'] = $user_id;

                $this->_redirect('user/user/myaccount/user_id/' . $row->user_id);
            }//end of inner if
        } else {
            $form->populate($row->toArray());
        }
        $this->view->placeholder('title')->set('Edit account');
//        $this->_helper->layout()->setLayout('layout');
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
            $this->_redirect('/');
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
                    $this->_redirect('/');
                }
            } else {
                $form->populate(array('email' => $resetEmail));
            }
        }//end of outer if
        $this->view->placeholder('title')->set('Reset Password');
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
        $this->_helper->layout()->setLayout('layout_wep');
        $this->view->blockManager()->enable('partial/dashboard.phtml');
    }

    public function logoutAction()
    {
        Zend_Session:: namespaceUnset('superadmin');
        $auth = Zend_Auth::getInstance()->getIdentity();
        Zend_Auth::getInstance()->clearIdentity();
        
        $this->_helper->FlashMessenger->addMessage(array('message' => 'Successfully logged out.'));

        $this->_redirect('');
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
        $mail['from'] = "support@iatistandard.org";
        $replace = "
You have requested to reset you account.
You may reset your password by clicking on this link or copying and pasting it in your browser:!reset_site!

Thank you.
------
!site_name!";
        $siteName = "AidStream";

        $url = "http://" . $_SERVER['HTTP_HOST'] . $this->view->baseUrl() . '/user/user/resetpassword';

        $uniqueId = md5(uniqid());

        $resetSite = "http://" . $_SERVER['HTTP_HOST'] . $this->view->baseUrl() . '/user/user/resetpassword/email/' . $toEmail . '/value/' . $uniqueId;

        $bodyTemp1 = str_replace('!reset_site!', $resetSite, $replace);

        $bodyTemp = str_replace('!view_url!', $url, $bodyTemp1);
        
        $message = str_replace('!site_name!', $siteName, $bodyTemp);

        $mail['message'] = $message;
        $mail['subject'] = 'Replacement login information for ' . $toEmail;
        $mail['to'] = $toEmail;
        
        $modelMail = new Model_Mail();
        $send = $modelMail->sendMail($mail);
        
        return $uniqueId;
    }
    
    
    
    public function preDispatch()
    {
        $this->_helper->layout()->setLayout('login_page');
    }

    public function testAction()
    {
        $test = new Model_Test();
        $role = $test->required();
        $this->view->userRole = $role;
    }
    
    public function masqueradeAction()
    {
        $accountAuth = Zend_Auth::getInstance();
        if($accountAuth->hasIdentity()){
            $identity = $accountAuth->getIdentity();
            if($identity->role == 'superadmin') {
                
                $account_id = $this->_getParam('org_id');
                $user_id = $this->_getParam('user_id');
                if(!$account_id || !$user_id){
                    $this->_helper->FlashMessenger->addMessage(array('error' => 'Could not masquerade. User information missing'));
                    $this->_redirect('/wep/dashboard');
                }
                $superAdminIdentity = $identity;
                $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
                $authAdapter->setTableName('user')
                    ->setIdentityColumn('user_id')
                    ->setCredentialColumn('account_id');
                    
                $authAdapter->setIdentity($user_id)
                    ->setCredential($account_id);
                $accountAuth->authenticate($authAdapter);
                $identity = $authAdapter->getResultRowObject();
    
                $rolevalue = new User_Model_DbTable_Role;
                $role = $rolevalue->getRoleById($identity->role_id);
                $std = new stdClass;
                $std->role = $role['role'];
    
                $identity = (object) array_merge((array) $identity, (array) $std);
                $accountAuth->getStorage()->write($identity);
                $session = new Zend_Session_Namespace('superadmin');
                $session->identity = serialize($superAdminIdentity);
                $this->_redirect('/wep/dashboard');
                
            } else {
                $this->_helper->FlashMessenger->addMessage(array('error' => 'You are not authorised to masquerade.'));
                $this->_redirect('/wep/dashboard');
            }
        } 
    }
    
    public function switchBackAction()
    {
        $auth = Zend_Auth::getInstance();
        if($auth->hasIdentity()){
            $session = new Zend_Session_Namespace('superadmin');
            if(isset($session->identity)){
                $auth->getStorage()->write(unserialize($session->identity));
                Zend_Session::namespaceUnset('superadmin');
                $this->_redirect('/admin/dashboard');
            } else {
                $this->_redirect('/wep/dashboard');
            }
        }
    }
    
    public function supportAction()
    {
        if(isset($_POST)){
            $data = $this->getRequest()->getPost();
            $form = new Form_General_Support();
            if($form->isValid($data)){
                $modelSupport = new Model_Support();
                //$modelSupport->saveSupportRequest($data);
                
                $mail['subject'] = 'Aidtype support requested';
                if($data['support_type'] == 'system'){
                    $mail['to'] = 'anjesh@yipl.com.np';
                }
                
                $mail['message'] = 'Support was requested by the following user:';
                $mail['message'] .=  "\nName: ".$data['support_name'];
                $mail['message'] .=  "\nEmail: ".$data['support_email'];
                $mail['message'] .= "\n\nQuery:\n".$data['support_query'];
                
                $modelMail = new Model_Mail();
                $send = $modelMail->sendMail($mail);
                
                $this->_helper->FlashMessenger->addMessage(array('message' =>'Thank you. Your query has been received.'));
                $this->_redirect('/');
            } else {
                $this->_helper->FlashMessenger->addMessage(array('error' => 'Please provide valid data'));
                $this->_redirect('/');
            }
        }
    }

}

