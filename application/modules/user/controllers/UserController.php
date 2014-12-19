<?php
/**
 * User Controller to render pages for user module
 * @author YIPL Dev Team
 *
 */
class User_UserController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout()->setLayout('login_page');
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function registerAction()
    {
        $formData = $this->getRequest()->getPost();
        $form = new User_Form_User_RegisterForm();
        $modelWep = new Model_Wep();
        if ($formData) {
            if ($form->isValid($formData)) {
                $userModel = new User_Model_User();
                $accountId = $userModel->registerUser($formData);

                $this->_helper->FlashMessenger
                    ->addMessage(array('message' => 'Thank you for registering.'
                                       .'You will receive an email shortly.'));
                $this->_redirect('/');
            } else {
                $this->_helper->FlashMessenger
                    ->addMessage(array('error' => 'Oops! something went wrong.'
                                       .' Please check the fields marked in red to proceed.'));
            }
        }
        $this->view->form = $form;
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

                $userModel = new User_Model_DbTable_User();
                $user = $userModel->getUserByEmail($email);
                if ($user) {
                    try {

                        $uniqueId = md5(uniqid());
                        $resetSite = "http://" . $_SERVER['HTTP_HOST']
                                . $this->view->baseUrl() . '/user/user/resetpassword/email/'
                                . urlencode($email) . '/value/' . urlencode($uniqueId);
                        $reset = new User_Model_DbTable_Reset();
                        $reset->insert(array('email' => $email, 'value' => $uniqueId, 'reset_flag' => '1'));

                        $notification = new Model_Notification;
                        $notification->sendResetNotifications($user , $resetSite);

                        $this->_helper->FlashMessenger
                            ->addMessage(array('message' => 'Further instructions'
                                            .' have been sent to your e-mail address.'));
                        $this->_redirect('/');
                    } catch (Exception $e) {
                        $this->_helper->FlashMessenger
                            ->addMessage(array('error' => 'Error in sending mail.'));
                    }//end of try catch
                } else {
                    $this->_helper->FlashMessenger
                        ->addMessage(array('error' => 'Sorry, ' . $email
                                           . ' is not a registered email in AidStream.'));
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
            } elseif ($identity->role == "user") {
                $this->_redirect('wep/dashboard');
            } elseif ($identity->role == "groupadmin") {
                $this->_redirect('group/dashboard');
            }
        }

        $request = $this->getRequest();
        $form = new User_Form_User_Login();
        if ($request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {

                $authAdapter = $this->getAuthAdapter();
                $username = $form->getValue('username');
                $password = $form->getValue('password');

                $authAdapter->setIdentity($username)
                        ->setCredential($password);
                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($authAdapter);

                if ($result->isvalid()) {

                    // check if user account has been disable
                    $model = new User_Model_DbTable_User();
                    $user = $model->getUserByUsername($username);
                    if (!$user['status']) {
                        if ($auth->hasIdentity()) {
                            $auth->clearIdentity();
                        }
                        $this->_helper
                            ->FlashMessenger
                            ->addMessage(array('error' => 'Your account has been disabled.'
                                            .' Please contact the system administrator'));
                        $this->_redirect('/');
                    }

                    $identity = $authAdapter->getResultRowObject(null , 'password');

                    //getting role from table role and merging it with $authAdapter->getResultRowObject()
                    // [adding role to identity]
                    $rolevalue = new User_Model_DbTable_Role;
                    $role = $rolevalue->getRoleById($identity->role_id);
                    $obj2 = new stdClass;
                    $obj2->role = $role['role'];

                    $identity = (object) array_merge((array) $identity, (array) $obj2);
                    $authStorage = $auth->getStorage();
                    $authStorage->write($identity);

                    $accModel = new User_Model_DbTable_Account();
                    $account = $accModel->getAccountRowByUserName('account' , 'id' , $identity->account_id);
                    $simplified = new Zend_Session_Namespace('simplified');
                    $simplified->simplified = $account->simplified;

                    $this->_helper->FlashMessenger->addMessage(array('message' => 'Successfully Logged In'));
                    if ($identity->role == 'superadmin') {
                        $this->_redirect('admin/dashboard');
                    } elseif ($identity->role == 'admin') {
                        $this->_redirect('wep/dashboard');
                    } elseif ($identity->role == 'user') {
                        $this->_redirect('wep/dashboard');
                    } elseif ($identity->role == 'groupadmin') {
                        $this->_redirect('group/dashboard');
                    }
                }
                else
                    $this->_helper->FlashMessenger
                        ->addMessage(array('error' => 'Username or password did not match.'));
                    //$this->_redirect('/');
            } else {
                $this->_helper->FlashMessenger
                    ->addMessage(array('error' => 'Username or password did not match.'));
                //$this->_redirect('/');
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
        $accountObj = new User_Model_DbTable_Account();
        $account = $accountObj->getAccountRowByUserName('account', 'id', $row->account_id);
        $this->view->account = $account;
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
        if($identity->role == 'user' || $identity->role == 'admin'){
            $this->view->blockManager()->enable('partial/primarymenu.phtml');
            $this->view->blockManager()->enable('partial/add-activity-menu.phtml');
            $this->view->blockManager()->enable('partial/published-list.phtml');
            $this->view->blockManager()->enable('partial/organisation-data.phtml');
            $this->view->blockManager()->enable('partial/download-my-data.phtml');
            $this->view->blockManager()->enable('partial/usermgmtmenu.phtml');
            $this->view->blockManager()->enable('partial/uploaded-docs.phtml');

            // for role user check if the user has permission to add, publish ,if not disable menu.
            if($identity->role == 'user'){
                $model = new Model_Wep();
                $userPermission = $model->getUserPermission($identity->user_id);
                $permission = $userPermission->hasPermission(Iati_WEP_PermissionConts::ADD_ACTIVITY);
                $publishPermission = $userPermission->hasPermission(Iati_WEP_PermissionConts::PUBLISH);
                if(!$permission){
                    $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
                }
                if(!$publishPermission){
                    $this->view->blockManager()->disable('partial/published-list.phtml');
                }
            }
        } elseif($identity->role == 'groupadmin') {
            $this->view->blockManager()->enable('partial/groupadmin-menu.phtml');
        } else {
            $this->view->blockManager()->enable('partial/superadmin-menu.phtml');
        }
    }

    public function removeAction()
    {
        $userName = $this->getRequest()->getParam('user_name');
        $user_id = $this->getRequest()->getParam('user_id');
        $accountObj = new User_Model_DbTable_Account();
        $accountObj->updateFileNameWithNull($userName);
        $this->_redirect('user/user/edit/user_id/' . $user_id);
    }

    public function editAction()
    {
        $user_id = $this->getRequest()->getParam('user_id');
        $auth = Zend_Auth::getInstance()->getIdentity();

        $roleName = $auth->role;
        $uploadDir = Zend_Registry::get('config')->upload_dir."/image/";
        //$uploadDir = APPLICATION_PATH.'/../public/uploads/image/';

        if ($user_id != $auth->user_id) {
            $this->_helper->FlashMessenger
                ->addMessage(array('error' => 'Access denied.'));
            $this->_redirect('/user/user/myaccount');
        }

        $userModel = new User_Model_DbTable_User();
        $row = $userModel->getUserById($user_id);
        $profileModel = new User_Model_DbTable_Profile();
        $row1 = $profileModel->getProfileByUserId($user_id);
        $accountObj = new User_Model_DbTable_Account();
        //$userName = strtok($row['user_name'], '_');
        $names = explode('_' , $row['user_name']);
        $last  = array_pop($names);
        $userName = implode('_' , $names); 

        $account = $accountObj->getAccountRowByUserName('account', 'username', $userName);
        $form = new User_Form_User_Edit();

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $data['name'] = $form->getValue('name');
                $data['address'] = $form->getValue('address');
                $data['telephone'] = $form->getValue('telephone');
                $data['twitter'] = (!$form->getValue('twitter')) ? $form->getValue('twitter') : '@' . preg_replace("/@/", "", $form->getValue('twitter'), 1);
                $data['first_name'] = $form->getValue('first_name');
                $data['middle_name'] = $form->getValue('middle_name');
                $data['last_name'] = $form->getValue('last_name');
                $data['email'] = $form->getValue('email');
                $data['url'] = $form->getValue('url');
                $data['disqus_comments'] = $form->getValue('disqus_comments');
                $accountObj->updateAccount($data, $userName);
                $value = $userModel->updateUser($data, $user_id);
                $profileModel->updateProfile($data, $user_id);
                
                if($roleName != 'user'){
                    $upload = new Zend_File_Transfer_Adapter_Http();
                    $upload->setDestination($uploadDir);
                    $upload->addFilter(new App_Filter_File_Resize(array(
						    'width' => 150,
						    'height' => 100,
						    'keepRatio' => true,
						)));
                    $source = $upload->getFileName();
                    if(is_string($source)) { $data['file_name'] = basename($source); }
                    try{
                       $upload->receive();
                       $accountObj->insertFileNameOrUpdate($data ,  $userName);
                    } catch(Zend_File_Transfer_Exception $e) {
                        $e->getMessage();
                    }
                }
                $this->_helper->FlashMessenger
                    ->addMessage(array('message' => 'Profile saved successfully.'));           
                $this->_redirect('user/user/myaccount/user_id/' . $row->user_id);
            }else{
                $form->populate($formData);
            }
        }else {
                $form->populate($row->toArray());
                $form->populate($row1->toArray());
                if($roleName != 'superadmin' && $roleName != 'groupadmin')
                {
                    $form->populate($account->toArray());
                }
        }
        $this->view->form = $form;
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
        if($identity->role == 'user' || $identity->role == 'admin'){
            $this->view->blockManager()->enable('partial/primarymenu.phtml');
            $this->view->blockManager()->enable('partial/add-activity-menu.phtml');
            $this->view->blockManager()->enable('partial/published-list.phtml');
            $this->view->blockManager()->enable('partial/organisation-data.phtml');
            $this->view->blockManager()->enable('partial/download-my-data.phtml');
            $this->view->blockManager()->enable('partial/usermgmtmenu.phtml');
            $this->view->blockManager()->enable('partial/uploaded-docs.phtml');
            // for role user check if the user has permission to add, publish ,if not disable menu.
            if($identity->role == 'user'){
                $model = new Model_Wep();
                $userPermission = $model->getUserPermission($identity->user_id);
                $permission = $userPermission->hasPermission(Iati_WEP_PermissionConts::ADD_ACTIVITY);
                $publishPermission = $userPermission->hasPermission(Iati_WEP_PermissionConts::PUBLISH);
                if(!$permission){
                    $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
                }
                if(!$publishPermission){
                    $this->view->blockManager()->disable('partial/published-list.phtml');
                }
            }
        } elseif ($identity->role == 'groupadmin') {
            $this->view->blockManager()->enable('partial/groupadmin-menu.phtml');
        } else {
            $this->view->blockManager()->enable('partial/superadmin-menu.phtml');
        }
    }

    /**
     *
     * @return unknown_type
     */
    public function resetpasswordAction()
    {
        $resetValue = $this->getRequest()->getParam('value');
        $resetEmail = $this->_getParam('email');
        $userModel = new User_Model_DbTable_User();
        $reset = new User_Model_DbTable_Reset();
        $resetResult = $reset->uniqueValue($resetEmail, $resetValue);
        if (!$resetResult) {
            $this->_helper->FlashMessenger
                ->addMessage(array('error' => 'You have already used this one-time reset link.'));
            $this->_redirect('/');
        } else {
            $resetId = $reset->getResetId($resetEmail, $resetValue);
            $form = new User_Form_User_Resetpassword();
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                $formData = $this->getRequest()->getPost();
                if ($form->isValid($formData)) {
                    $email = $form->getValue('email');
                    $password = $form->getValue('password');

                    //update the password in user table
                    $data['password'] = md5($password);
                    $isupdated = $userModel->updateUserByEmail($data, $resetEmail);
                    if($isupdated){
                        //update the reset value in reset table
                        $resetData['reset_flag'] = 0;
                        $reset->update($resetData, array('reset_id' => $resetId));
                        $this->_helper->FlashMessenger
                            ->addMessage(array('message' => 'Your password has been changed sucessfully.'));
                    } else {
                        $this->_helper->FlashMessenger
                            ->addMessage(array('error' => 'Sorry some error occured please try again later.'));
                    }
                    $this->_redirect('/');
                }
            } else {
                $form->populate(array('email' => $resetEmail));
            }
        }//end of outer if
    }

    //end of else

    public function changepasswordAction()
    {
        $user_id = $this->getRequest()->getParam('user_id');
        $auth = Zend_Auth::getInstance()->getIdentity();

        if ($user_id != $auth->user_id) {
            $this->_helper->FlashMessenger
                ->addMessage(array('error' => 'Access denied.'));
            $this->_redirect('/user/user/myaccount');
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


                        $this->_helper->FlashMessenger
                            ->addMessage(array('message' => 'Changed password successfully.'));

                        $this->_redirect('user/user/myaccount');
                    } else {

                        $this->_helper->FlashMessenger
                            ->addMessage(array('error' => 'Old password did not match.'));
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

    public function changeusernameAction()
    {
        $user_id = $this->getRequest()->getParam('user_id');
        $auth = Zend_Auth::getInstance()->getIdentity();

        if ($user_id != $auth->user_id) {
            $this->_helper->FlashMessenger
                ->addMessage(array('error' => 'Access denied.'));
            $this->_redirect('/user/user/myaccount');
        }

        $userModel = new User_Model_DbTable_User();
        $user = $userModel->getUserById($user_id);

        $form = new User_Form_User_Changeusername();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $formdata = $this->getRequest()->getPost();

            if ($form->isValid($formdata)) {
                try {
                    if ($auth->role == 'admin') {
                        $old_account_identifier = strstr($user->user_name, '_', TRUE);
                        $account_identifier = $this->_getParam('account_identifier');

                        $userModel->changeAdminUsername($old_account_identifier, $account_identifier, $auth->account_id);
                        $auth->user_name = $account_identifier . '_admin';
                    } elseif ($auth->role == 'groupadmin') {
                        $old_group_identifier = strstr($user->user_name, '_', TRUE);
                        $group_identifier = $this->_getParam('group_identifier');
                        
                        $userModel->changeGroupadminUsername($old_group_identifier, $group_identifier, $auth->user_id);
                        $auth->user_name = $group_identifier . '_group';
                    }
                    $this->_helper->FlashMessenger
                        ->addMessage(array('message' => 'Username changed successfully. Please use the new username next time you log in.'));

                    $this->_redirect('user/user/myaccount');
                    
                } catch (Exception $e) {
                    print 'Cannot update username. Error Occured.';
                }//end of try catch*/
            }
        }
        $this->view->user = $user;
        $this->_helper->layout()->setLayout('layout_wep');
        $this->view->blockManager()->enable('partial/dashboard.phtml');
    }

    public function logoutAction()
    {
        Zend_Session:: namespaceUnset('superadmin');
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::forgetMe();

        $this->_helper->FlashMessenger
            ->addMessage(array('message' => 'Successfully logged out.'));

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

    public function masqueradeAction()
    {
        $accountAuth = Zend_Auth::getInstance();
        if($accountAuth->hasIdentity()){
            $identity = $accountAuth->getIdentity();
            if($identity->role == 'superadmin' || $identity->role == 'groupadmin') {
                $identity_role = $identity->role;
                $account_id = $this->_getParam('org_id');
                $user_id = $this->_getParam('user_id');
                if(!$account_id || !$user_id){
                    $this->_helper->FlashMessenger
                        ->addMessage(array('error' => 'Could not masquerade. User information missing'));
                    $this->_redirect('/wep/dashboard');
                }
                $adminIdentity = $identity;
                $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
                $authAdapter->setTableName('user')
                    ->setIdentityColumn('user_id')
                    ->setCredentialColumn('account_id');

                $authAdapter->setIdentity($user_id)
                    ->setCredential($account_id);
                $accountAuth->authenticate($authAdapter);
                $identity = $authAdapter->getResultRowObject(null, 'password');
                $rolevalue = new User_Model_DbTable_Role;
                $role = $rolevalue->getRoleById($identity->role_id);
                $std = new stdClass;
                $std->role = $role['role'];

                $identity = (object) array_merge((array) $identity, (array) $std);
                $accountAuth->getStorage()->write($identity);

                $accModel = new User_Model_DbTable_Account();
                $account = $accModel->getAccountRowByUserName('account' , 'id' , $identity->account_id);
                $simplified = new Zend_Session_Namespace('simplified');
                $simplified->simplified = $account->simplified;

                if ($identity_role == 'superadmin') {
                    $session = new Zend_Session_Namespace('superadmin');
                    $session->identity = serialize($adminIdentity);
                } elseif ($identity_role == 'groupadmin') {
                    $session = new Zend_Session_Namespace('groupadmin');
                    $session->identity = serialize($adminIdentity);
                }
                $this->_redirect('/wep/dashboard');

            } else {
                $this->_helper->FlashMessenger
                    ->addMessage(array('error' => 'You are not authorised to masquerade.'));
                $this->_redirect('/wep/dashboard');
            }
        }
    }

    public function switchBackAction()
    {
        $auth = Zend_Auth::getInstance();
        if($auth->hasIdentity()){
            $session = new Zend_Session_Namespace('superadmin');
            if (!isset($session->identity)) $session = new Zend_Session_Namespace('groupadmin');
            if (Zend_Session::namespaceIsset('superadmin')) {
                if(isset($session->identity)){
                    $auth->getStorage()->write(unserialize($session->identity));
                    Zend_Session::namespaceUnset('superadmin');
                    $this->_redirect('/admin/list-organisation');
                } else {
                    $this->_redirect('/wep/dashboard');
                }
            } elseif (Zend_Session::namespaceIsset('groupadmin')) {
                if(isset($session->identity)){
                    $auth->getStorage()->write(unserialize($session->identity));
                    Zend_Session::namespaceUnset('groupadmin');
                    $this->_redirect('/group/list-organisation');
                } else {
                    $this->_redirect('/group/dashboard');
                }
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
                $modelSupport->saveSupportRequest($data);

                $notification = new Model_Notification;
                $notification->sendSupportNotifications($data);

                $this->_helper->FlashMessenger
                    ->addMessage(array('message' =>'Thank you. Your query has been received.'));
            } else {
                $this->_helper->FlashMessenger
                    ->addMessage(array('error' => 'Sorry your support mail could not be sent'));
            }

            if($this->_getParam('referer')){
                $this->_redirect($this->_getParam('referer'));
            } else {
                $this->_redirect('/wep/dashboard');
            }
        }
    }

}

