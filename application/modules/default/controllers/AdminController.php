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
        
        $defaultFieldGroup = new Iati_WEP_UserAccountDisplayField();
        $default['fields'] = $defaultFieldGroup->getProperties();
        $form = new Form_Admin_Userregister();
        $form->add($default);
        if ($this->getRequest()->isPost()) {
            try {
                $data = $this->getRequest()->getPost();
                $model = new Model_Wep();
                if (!$form->isValid($data)) {
                    $form->populate($data);
                } else {
                    $account_username = $model->getAccountUserName($identity->account_id);
                    $user['user_name'] = $account_username . "_" .$data['user_name'];
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
                        $defaultKey[$i] = $eachField;
                        $defaultFieldGroup->setProperties($eachField);
                        $i++;
                    }                    
                    $fieldString = serialize($defaultFieldGroup);
                    $defaultFields['object'] = $fieldString;
                    $defaultFields['user_id'] = $user_id;
                    $defaultFieldId = $model->insertRowsToTable('default_user_field', $defaultFields);

                    $privilegeFields['resource'] = serialize($defaultKey);
                    $privilegeFields['owner_id'] = $user_id;
                    $privilegeFieldId = $model->insertRowsToTable('Privilege', $privilegeFields);

                    $this->_helper->FlashMessenger->addMessage(array('message' => "Account successfully registered."));
                    $this->_redirect('user/user/login');
                }
            } catch (Exception $e) {

            }
        }
        $this->view->form = $form;
        $this->view->blockManager()->enable('partial/login.phtml');
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
    }
    
    public function deleteUserAction(){
        $identity = Zend_Auth::getInstance()->getIdentity();
        if($identity->role == 'admin' || $identity->role = 'superadmin'){
            if(isset($_GET['user_id'])){
                try{
                    $user_id = $_GET['user_id'];
                    $userModel = new User_Model_DbTable_User();
                    $$userModel->deleteUser($user_id);
                    $profileModel = new User_Model_DbTable_Profile();
                    $profileModel->deleteProfile($user_id);
                    $this->_helper->FlashMessenger->addMessage(array('message' => 'User Deleted.'));
                    $this->_redirect('admin/list-users');
                }
                catch(Exception $e){
                    
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
        
    }
    
    public function editUserPermissionAction()
    {
        if(isset($_GET['user_id'])){
                $user_id = $_GET['user_id'];
        }
        $model = new Model_Wep();
        $permissionSerialized = $model->getRowById('default_user_field', 'user_id', $user_id);
        $permissionObj = unserialize($permissionSerialized['object']);
        $default['fields'] = $permissionObj->getProperties();
        
        $form = new Form_Admin_Editpermission();
        $form->edit($default);
        
        if($_POST){
            try{
                //print_r($_POST);exit;
                
                $data = $_POST;
                $i = 0;
                foreach ($data['default_fields'] as $eachField) {
                    $defaultKey[$i] = $eachField;
                    $permissionObj->setProperties($eachField);
                    $i++;
                }                    
                $fieldString = serialize($permissionObj);
                $defaultFields['object'] = $fieldString;
                $defaultFields['user_id'] = $user_id;
                $defaultFieldId = $model->updateRow('default_user_field', $defaultFields, 'user_id', $user_id);
                //print_r($defaultFieldId);exit;
                $privilegeFields['resource'] = serialize($defaultKey);
                $privilegeFields['owner_id'] = $user_id;
                $privilegeFieldId = $model->updateRow('Privilege', $privilegeFields, 'owner_id', $user_id);
                
                $this->_helper->FlashMessenger->addMessage(array('message' => 'User permission updated.'));
                $this->_redirect('user/user/login'); 
            } 
            catch(Exception $e){
             print $e;   
            }
        }
        $this->view->form = $form;
        $this->_helper->layout()->setLayout('layout_wep');
        $this->view->blockManager()->enable('partial/dashboard.phtml');
        $this->view->blockManager()->enable('partial/primarymenu.phtml');
                
        
    }
    
    public function resetUserPasswordAction()
    {
        
    }
    
    public function listAllUsersAction()
    {
        
    }
    
    public function addRole()
    {
        
    }

}