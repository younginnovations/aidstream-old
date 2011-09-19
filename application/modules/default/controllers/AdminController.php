<?php

class AdminController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout()->setLayout('layout_wep');
        /* $contextSwitch = $this->_helper->contextSwitch;
          $contextSwitch->addActionContext('', 'json')
          ->initContext('json'); */
    }

    public function indexAction()
    {
        /* $identity = Zend_Auth::getInstance()->getIdentity();
          if($identity){
          } */
        $this->_redirect('admin/dashboard');
    }

    public function dashboardAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $this->view->user = $identity;
        $this->view->placeholder('title')->set("Admin Dashboard");
        //$this->_helper->layout()->setLayout('layout_wep');
        $this->view->blockManager()->enable('partial/dashboard.phtml');
    }

    public function awaitingAction()
    {
        $userModel = new Model_User();
        $data = $userModel->getAwaitingUser();
        $this->view->data = $data->toArray();
        $this->view->placeholder('title')->set("Awaiting Approval");
        //$this->view->blockManager()->enable('partial/blocks/adminmenu.phtml');
        $this->_helper->layout()->setLayout('layout_wep');
        $this->view->blockManager()->enable('partial/dashboard.phtml');
    }

    public function approveAction()
    {
        $id = $this->getRequest()->getParam('id');
        if (!$id) {
            throw new Exception('Invalid Request');
        }
        $status = array('status' => 1);
        $userModel = new Model_User();
        $result = $userModel->updateStatus($status, $id);
        if ($result) {
            //@todo email params
            /* $mailerParams = array('email'=> 'abhinav@yipl.com.np');
              $toEmail = 'manisha@yipl.com.np';
              $template = 'user-approve';
              $Wep = new App_Notification;
              $Wep->sendemail($mailerParams,$toEmail,$template); */
            $this->_helper->FlashMessenger->addMessage(array('message' => "Your Changes have been saved."));
            $this->_redirect('admin/awaiting');
            $this->view->blockManager()->enable('partial/dashboard.phtml');
        }
    }

    public function rejectAction()
    {
        $id = $this->getRequest()->getParam('id');
        if (!$id) {
            throw new Exception('Invalid Request');
        }
        $status = array('status' => 3);
        $userModel = new Model_User();
        $result = $userModel->updateStatus($status, $id);
        if ($result) {
            $this->_helper->FlashMessenger->addMessage(array('message' => "Your Changes have been saved."));
            $this->_redirect('admin/awaiting');
        }
    }

    public function listOrganisationAction()
    {
        $model = new Model_Wep();
        $this->view->rowSet = $model->listOrganisation('account');
        $this->view->placeholder('title')->set("Organisation List");
        $this->view->blockManager()->enable('partial/dashboard.phtml');
    }

    public function viewAction()
    {
        if ($this->getRequest()->isGet()) {/*
          print_r($this->_request->getParam('id'));
          exit(); */

            $user_id = $this->_request->getParam('id');
            $wep = new Model_Wep();
            $user_info = $wep->listAll('user', 'user_id', $user_id);
//            print_r($user_info);exit();
            if ($user_info) {
                $user_profile = $wep->listAll('profile', 'user_id', $user_id);
                $account_info = $wep->listAll('account', 'id', $user_info[0]['account_id']);

                $this->view->user_info = $user_info[0];
                $this->view->user_profile = $user_profile[0];
                $this->view->account_info = $account_info[0];
            } else {
                $this->view->message = "The user does not exist.";
            }
        }
        $this->view->blockManager()->enable('partial/dashboard.phtml');
    }

    public function editOrganisationAction()
    {
        /* if($this->getRequest()->isGet()){
          $newArray = array();
          $id = $this->_request->getParam('id');
          $model = new Model_Wep();
          $rowSet = $model->getRowById('account', 'id', $id);
          $newArray['organisation_name'] = $rowSet['name'];
          $newArray['organisation_address'] = $rowSet['address'];
          $newArray['organisation_username'] = $rowSet['username'];

          $rowSet = $model->getRowById('user', 'account_id')

          } */
        $this->view->blockManager()->enable('partial/dashboard.phtml');
    }

    public function registerUserAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $adminId = $identity->user_id;
        $accountId = $identity->account_id;
        $model = new Model_Wep();
        //$admindefaultField = $model->getDefaults('default_field_groups', 'account_id', $identity->account_id);
        
        $defaultFieldGroup = new Iati_WEP_UserPermission();
        $default['fields'] = $defaultFieldGroup->getProperties();
        $form = new Form_Admin_Userregister();
        $form->add($default);
        if ($this->getRequest()->isPost()) {
            try {
                $data = $this->getRequest()->getPost();
                $account_username = $model->getAccountUserName($identity->account_id);
                $user_name = $account_username . "_" .$data['user_name'];
                $usernameExists = $model->userExists('user_name', $user_name);
                $emailExists = $model->userExists('email', $data['email']);
                if (!$form->isValid($data)) {
                    $form->populate($data);
                }
                //@todo check for unique username. fix the bug
                else if (!empty($usernameExists)) {
                    $this->_helper->FlashMessenger->addMessage(array('error' => "Username already exists."));
                    $form->populate($data);
                }
                else if (!empty($emailExists)) {
                    $this->_helper->FlashMessenger->addMessage(array('error' => "User already exists."));
                    $form->populate($data);
                }else {
                    $model = new Model_Wep();
                    
                    $user['user_name'] = $user_name;
                    $user['password'] = md5($data['password']);
                    $user['role_id'] = 2; //id resembels user as role
                    $user['email'] = $data['email'];
                    $user['account_id'] = $accountId;
                    $user['status'] = 1;                   
                    $user_id = $model->insertRowsToTable('user', $user);

                    $information['first_name'] = $data['first_name'];
                    $information['middle_name'] = $data['middle_name'];
                    $information['last_name'] = $data['last_name'];
                    $information['user_id'] = $user_id;
                    $profile_id = $model->insertRowsToTable('profile', $information);
                    $i = 0;
                    foreach ($data['default_fields'] as $eachField) {
                        //$defaultKey[$i] = $eachField;
                        //if($each)
                        //$defaultFieldGroup->setProperties($eachField);
                        //$i++;
                        if($eachField == 'add'){
                        $defaultFieldGroup->setProperties('add_activity_elements');
                        $defaultFieldGroup->setProperties('add_activity');
                        $defaultKey[$i++] = 'add_activity_elements';
                        $defaultKey[$i++] = 'add_activity';
                    }
                    elseif($eachField == 'edit'){
                        $defaultFieldGroup->setProperties('edit_activity_elements');
                        $defaultFieldGroup->setProperties('edit_activity');
                        $defaultKey[$i++] = 'edit_activity_elements';
                        $defaultKey[$i++] = 'edit_activity';
                    }
                    else if($eachField == 'delete'){
                        $defaultFieldGroup->setProperties('delete_activity');
                        $defaultKey[$i++] = 'delete_activity';
                    }
                    else{
                        $defaultFieldGroup->setProperties($eachField);
                        $defaultKey[$i++] = $eachField;
                    }
                    }                    
                    $fieldString = serialize($defaultFieldGroup);
                    $defaultFields['object'] = $fieldString;
                    $defaultFields['user_id'] = $user_id;
                    $defaultFieldId = $model->insertRowsToTable('user_permission', $defaultFields);
                    $privilegeFields['resource'] = serialize($defaultKey);
                    $privilegeFields['owner_id'] = $user_id;
                    $privilegeFieldId = $model->insertRowsToTable('Privilege', $privilegeFields);

                    $this->_helper->FlashMessenger->addMessage(array('message' => "Account successfully registered."));
                    $this->_redirect('user/user/login');
                }
            } catch (Exception $e) {
                print_r($e);exit;
            }
        }
        $this->view->form = $form;
        $this->_helper->layout()->setLayout('layout_wep');
        $this->view->blockManager()->enable('partial/dashboard.phtml');
        $this->view->blockManager()->enable('partial/primarymenu.phtml');
        $this->view->blockManager()->enable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->enable('partial/usermgmtmenu.phtml');
    }
    
    public function listUsersAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $model = new Model_Wep();
        //print_r($identity->account_id);exit;
        $usersList = $model->getUsersByAccountId('user', $identity->account_id, array('role_id' => '2'));
        //print_r($usersList);exit;
        $this->view->users = $usersList;
        //$this->_helper->layout()->setLayout('layout_wep');
        $this->view->blockManager()->enable('partial/dashboard.phtml');
        $this->view->blockManager()->enable('partial/primarymenu.phtml');
        $this->view->blockManager()->enable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->enable('partial/usermgmtmenu.phtml');
    }
    
    public function deleteUserAction(){
        $identity = Zend_Auth::getInstance()->getIdentity();
        if($identity->role == 'admin' || $identity->role = 'superadmin'){
            if(isset($_GET['user_id'])){
                try{
                    $user_id = $_GET['user_id'];
                    $userModel = new User_Model_DbTable_User();
                    $userModel->deleteUser($user_id);
                    $profileModel = new User_Model_DbTable_Profile();
                    $profileModel->deleteProfile($user_id);
                    $wepModel = new Model_Wep();
                    $wepModel->deleteRow('user_permission', 'user_id', $user_id);
                    $wepModel->deleteRow('Privilege', 'owner_id', $user_id);
                    $this->_helper->FlashMessenger->addMessage(array('message' => 'User Deleted.'));
                    $this->_redirect('admin/list-users');
                }
                catch(Exception $e){
                 print $e->getMessage();exit;  
                }
            }
        }
        else{
            $this->_helper->FlashMessenger->addMessage(array('error' => 'Access Denied.'));
            $this->_redirect('user/user/login');        
        
        }
    }
    
    public function viewProfileAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        if($identity->role == 'admin' || $identity->role = 'superadmin'){
            
            if(isset($_GET['user_id'])){
                try{
                    $user_id = $_GET['user_id'];
                    $userModel = new User_Model_DbTable_User();
                    $row = $userModel->getUserById($user_id);
                    $profileModel = new User_Model_DbTable_Profile();
                    $row1 = $profileModel->getProfileByUserId($user_id);
                    $this->view->profile = (!empty($row1))?$row1->toArray():null;
                    $this->view->user = (!empty($row))?$row->toArray():null;
                }
                catch(Exception $e){
                    
                }
                
                
            }
        }
        else{
            $this->_helper->FlashMessenger->addMessage(array('error' => 'Access Denied.'));
            $this->_redirect('user/user/login');        
        
        }
        $this->_helper->layout()->setLayout('layout_wep');
        $this->view->blockManager()->enable('partial/dashboard.phtml');
        $this->view->blockManager()->enable('partial/primarymenu.phtml');
        $this->view->blockManager()->enable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->enable('partial/usermgmtmenu.phtml');
    }
    
    public function editUserPermissionAction()
    {
        if(isset($_GET['user_id'])){
                $user_id = $_GET['user_id'];
        }
        $model = new Model_Wep();
        $permissionSerialized = $model->getRowById('user_permission', 'user_id', $user_id);
        //print_r($permissionSerialized['object']);exit;
        $permissionObj = unserialize($permissionSerialized['object']);
        //print_r($permissionObj);exit;
        $default['fields'] = $permissionObj->getProperties();
        
        $form = new Form_Admin_Editpermission();
        $form->edit($default);
        
        if($_POST){
            try{
                
                $data = $_POST;
                $i = 0;
                //print_r($data);exit;
                $permissionObj = new Iati_WEP_UserPermission();
                foreach ($data['default_fields'] as $eachField) {
                    if($eachField == 'add'){
                        $permissionObj->setProperties('add_activity_elements');
                        $permissionObj->setProperties('add_activity');
                        $defaultKey[$i++] = 'add_activity_elements';
                        $defaultKey[$i++] = 'add_activity';
                    }
                    elseif($eachField == 'edit'){
                        $permissionObj->setProperties('edit_activity_elements');
                        $permissionObj->setProperties('edit_activity');
                        $defaultKey[$i++] = 'edit_activity_elements';
                        $defaultKey[$i++] = 'edit_activity';
                    }
                    else if($eachField == 'delete'){
                        $permissionObj->setProperties('delete_activity');
                        $defaultKey[$i++] = 'delete_activity';
                    }
                    else{
                        $permissionObj->setProperties($eachField);
                        $defaultKey[$i++] = $eachField;
                    }
                    
                    //$defaultKey[$i] = $eachField;
                    
                    //$i++;
                }
                $permissionObj->setProperties('view_activities');
                $fieldString = serialize($permissionObj);
                $defaultFields['object'] = $fieldString;
                $defaultFields['user_id'] = $user_id;
                $defaultFieldId = $model->updateRow('user_permission', $defaultFields, 'user_id', $user_id);
                //print_r($defaultKey);exit;
                $privilegeFields['resource'] = serialize($defaultKey);
                $privilegeFields['owner_id'] = $user_id;
                $privilegeFieldId = $model->updateRow('Privilege', $privilegeFields, 'owner_id', $user_id);
                
                $this->_helper->FlashMessenger->addMessage(array('message' => 'User permission updated.'));
                $this->_redirect('admin/view-profile?user_id='.$user_id); 
            } 
            catch(Exception $e){
             print $e;   
            }
        }
        $this->view->form = $form;
        $this->_helper->layout()->setLayout('layout_wep');
        $this->view->blockManager()->enable('partial/dashboard.phtml');
        $this->view->blockManager()->enable('partial/primarymenu.phtml');
        $this->view->blockManager()->enable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->enable('partial/usermgmtmenu.phtml');        
        
    }
    
    public function resetUserPasswordAction()
    {
        if($_GET['user_id']){
            $user_id = $this->getRequest()->getParam('user_id');
             $form = new Form_Admin_ResetUserPassword();
            $this->view->form = $form;$this->view->form = $form;
            if($this->getRequest()->isPost()){
                $formdata = $this->getRequest()->getPost();

                if (!$form->isValid($formdata)) {
                    $form->populate($formdata);
                } else {
                    try {
                        $model = new User_Model_DbTable_User();
                        $data['password'] = $this->getRequest()->getParam('password');
                        $model->changePassword($data, $user_id);
                        $this->_helper->FlashMessenger->addMessage(array('message' => 'Changed password successfully.'));

                        $this->_redirect('admin/list-users');
                       
                    } catch (Exception $e) {
                        print 'Error Occured';
                        print $e->getMessage();
                    }//end of try catch
                }
            }
           
            $this->_helper->layout()->setLayout('layout_wep');
            $this->view->blockManager()->enable('partial/dashboard.phtml');
            $this->view->blockManager()->enable('partial/primarymenu.phtml');
            $this->view->blockManager()->enable('partial/add-activity-menu.phtml');
            $this->view->blockManager()->enable('partial/usermgmtmenu.phtml');
        }else{
            print "no"; exit;
        }
    }
    
    public function listAllUsersAction()
    {
        
    }
    
    public function addRole()
    {
        
    }

}