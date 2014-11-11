<?php
/**
 * Controller to handle actions for the superadmin
 *
 * @author YIPL Dev team
 */
class AdminController extends Zend_Controller_Action
{

    public function init()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $this->_helper->layout()->setLayout('layout_wep');
        $this->view->blockManager()->enable('partial/dashboard.phtml');
        if($identity->role === 'superadmin'){
            $this->view->blockManager()->enable('partial/superadmin-menu.phtml');
        } else {
            $this->view->blockManager()->enable('partial/primarymenu.phtml');
            $this->view->blockManager()->enable('partial/add-activity-menu.phtml');
            $this->view->blockManager()->enable('partial/download-my-data.phtml');
            $this->view->blockManager()->enable('partial/usermgmtmenu.phtml');
            $this->view->blockManager()->enable('partial/published-list.phtml');
            $this->view->blockManager()->enable('partial/uploaded-docs.phtml');
            if(!Simplified_Model_Simplified::isSimplified()){
                $this->view->blockManager()->enable('partial/organisation-data.phtml');
            } else {
                $this->view->blockManager()->enable('partial/simplified-info.phtml');
            }
        }

    }

    public function indexAction()
    {
        //$this->_redirect('admin/dashboard');
    }

    public function dashboardAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $this->view->user = $identity;
        //$this->view->placeholder('title')->set("Admin Dashboard");
        //$this->_helper->layout()->setLayout('layout_wep');
    }

    public function listOrganisationAction()
    {
        $model = new Model_Wep();
        $userModel = new Model_User();
        $activity_model = new Model_ActivityCollection();
        $orgs = $model->listOrganisation('account');
        $org_data = array();
        foreach($orgs as $organisation)
        {
            $users = $userModel->getUserCountByAccountId($organisation['id']);
            $organisation['users_count'] = $users[0]['users_count'];
            $activities = $activity_model->getActivitiesCountByAccount($organisation['id']);
            $organisation['activity_count'] = $activities[0]['activity_count'];
            $user = $userModel->getUserByAccountId($organisation['id'],array('role_id'=>1));
            $organisation['user_id'] = $user['user_id'];
            $organisation['email'] = $user['email'];
            $org_data[] = $organisation;
        }

        $this->view->rowSet = $org_data;

    }

    public function editOrganisationAction()
    {
        if($this->getRequest()->isGet()){
            $org_info = array();
            $id = $this->_request->getParam('id');
            $model = new Model_Wep();

            $rowSet = $model->getRowById('account', 'id', $id);
            $org_info['organisation_name'] = $rowSet['name'];
            $org_info['organisation_address'] = $rowSet['address'];
            $org_info['organisation_username'] = $rowSet['username'];

            $userModel = new Model_User();
            $user_info = $userModel->getUserByAccountId($rowSet['id'],array('role_id'=>1));
            $user_profile = $model->getRowById('profile','user_id',$user_info['user_id']);
            $user = $model->getRowById('user', 'user_id', $user_info['user_id']);
            $user_info['admin_username'] = $user['user_name'];

            //Create edit form
            $defaultFieldsValues = $model->getDefaults(
                                                       'default_field_values',
                                                       'account_id',
                                                       $rowSet['id']
                                                    );
            $default['field_values'] = $defaultFieldsValues->getDefaultFields();
            $defaultFieldGroup = $model->getDefaults(
                                                     'default_field_groups',
                                                     'account_id',
                                                     $rowSet['id']
                                                );
            $default['fields'] = $defaultFieldGroup->getProperties();

            $form = new Form_Wep_Accountregister();
            $form->add($default);

            $form->addElement('hidden','org_id',array('value'=>$rowSet['id']));
            $form->addElement('hidden','user_id',array('value'=>$user_info['user_id']));
            $form->addElement('hidden','profile_id',array('value'=>$user_profile['id']));

            $form->populate($org_info);
            $form->populate($user_info);
            $form->populate($user_profile);

            //Disable name and username as they should not be edited
            $form->organisation_name->setAttrib('readonly','true');
            $form->organisation_username->setAttrib('readonly','true');
            $form->admin_username->setAttrib('readonly','true');
            $form->Signup->setLabel('Save');
            $form->setAction($this->view->baseUrl().'/admin/update-organisation');

            $this->view->form = $form;
        }
    }

    public function updateOrganisationAction()
    {
        if(!empty($_POST)){
            $data = $this->getRequest()->getPost();
            $model = new Model_Wep();

            $account_id = $org_id = $data['org_id'];
            $user_id = $data['user_id'];
            $profile_id = $data['profile_id'];

            // Remove password element if password is empty.
            if(!$data['password']){
                unset($data['password']);
                unset($data['confirmpassword']);
            }

            $defaultFieldsValues = $model->getDefaults(
                                                       'default_field_values',
                                                       'account_id',
                                                       $org_id
                                                    );
            $default['field_values'] = $defaultFieldsValues->getDefaultFields();
            $defaultFieldGroup = $model->getDefaults(
                                                     'default_field_groups',
                                                     'account_id',
                                                     $org_id
                                                );
            $default['fields'] = $defaultFieldGroup->getProperties();

            $form = new Form_Wep_Accountregister();
            $form->add($default);
            $form->organisation_username->clearValidators();

            if($form->isValidPartial($data)){
                $account['address'] = $data['organisation_address'];
                $model->updateRow('account', $account,'id',$org_id);

                //Update User Info
                if($data['password']){
                    $user['password'] = md5($data['password']);
                }
                $user['email'] = $data['email'];
                $user['user_name'] = $data['admin_username'];
                $user_id = $model->updateRow('user', $user,'user_id',$user_id);

                $admin['first_name'] = $data['first_name'];
                $admin['middle_name'] = $data['middle_name'];
                $admin['last_name'] = $data['last_name'];
                $admin_id = $model->updateRow('profile', $admin,'id',$profile_id);

                //Update defaults
                $default = new Model_Defaults();
                $default->updateDefaults($data , $org_id);

                $privilegeFields['resource'] = serialize($defaultKey);
                $privilegeFieldId = $model->updateRow(
                                                      'Privilege',
                                                      $privilegeFields,
                                                      'owner_id',
                                                      $account_id
                                                    );

                $this->_helper->FlashMessenger
                    ->addMessage(array('message' => "Organisation Information
                                       Sucessfully Updated."));
                $this->_redirect('/admin/edit-organisation/?id='.$org_id);

            } else {
                $form->populate($data);
                $form->addElement('hidden','org_id',array('value'=>$org_id));
                $form->addElement('hidden','user_id',array('value'=>$user_id));
                $form->addElement('hidden','profile_id',array('value'=>$profile_id));
                $form->organisation_name->setAttrib('readonly','true');
                $form->organisation_username->setAttrib('readonly','true');
                $form->admin_username->setAttrib('readonly','true');
                $form->Signup->setLabel('Save');
            }
            $this->view->form = $form;
        } else {
            $this->_redirect('admin/register');
        }
    }

    public function deleteOrganisationAction()
    {
        $orgId = $this->_getParam('org_id');
        $model = new Model_Admin();
        $model->deleteOrganisationById($orgId);
        $this->_helper->FlashMessenger->addMessage(array('message' => 'Organisation Deleted.'));
        $this->_redirect('admin/list-organisation');
    }

    /**
     * Function to register an organisation by Superadmin
     */

    public function registerAction()
    {
        $auth = Zend_Auth::getInstance();
        if($auth->hasIdentity()){
            $is_admin = false;
            $identity = $auth->getIdentity();
            if($identity->role == "superadmin"){
                $this->view->blockManager()->disable('partial/primarymenu.phtml');
                $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
                $this->view->blockManager()->disable('partial/usermgmtmenu.phtml');
                $this->view->blockManager()->disable('partial/published-list.phtml');
                $this->view->blockManager()->enable('partial/superadmin-menu.phtml');
                $this->view->blockManager()->enable('partial/dashboard.phtml');
                $is_admin = true;
            }
        }

        $defaultFieldsValues = new Iati_WEP_AccountDefaultFieldValues();
        $default['field_values'] = $defaultFieldsValues->getDefaultFields();
        $defaultFieldGroup = new Iati_WEP_AccountDisplayFieldGroup();
        $default['fields'] = $defaultFieldGroup->getProperties();
        $form = new Form_Wep_Accountregister();
        $form->add($default);

        if ($this->getRequest()->isPost()) {
            try {
                $data = $this->getRequest()->getPost();
                $model = new Model_Wep();
                if (!$form->isValid($data)) {
                    $form->populate($data);
                } else {
                    //Save Account Info
                    $account['name'] = $data['organisation_name'];
                    $account['address'] = $data['organisation_address'];
                    $account['username'] = $data['organisation_username'];
                    $account['uniqid'] = md5(date('Y-m-d H:i:s'));
                    $account_id = $model->insertRowsToTable('account', $account);

                    //Save User Info
                    $user['user_name'] = trim($data['organisation_username']) . "_admin";
                    $user['password'] = md5($data['password']);
                    $user['role_id'] = 1;
                    $user['email'] = $data['email'];
                    $user['account_id'] = $account_id;
                    $user['status'] = 1;
                    $user_id = $model->insertRowsToTable('user', $user);

                    //Save User Profile
                    $admin['first_name'] = $data['first_name'];
                    $admin['middle_name'] = $data['middle_name'];
                    $admin['last_name'] = $data['last_name'];
                    $admin['user_id'] = $user_id;
                    $admin_id = $model->insertRowsToTable('profile', $admin);

                    //Save Default Fields
                    $default = new Model_Defaults();
                    $default->createDefaults($data , $account_id);

                    $privilegeFields['resource'] = serialize($defaultKey);
                    $privilegeFields['owner_id'] = $account_id;
                    $privilegeFieldId = $model->insertRowsToTable(
                                                                  'Privilege',
                                                                  $privilegeFields
                                                            );

                    //Send notification
                    $data['user_name']  = $user['user_name'];
                    $notification = new Model_Notification;
                    $notification->sendRegistrationNotifications($data);

                    $this->_helper->FlashMessenger
                        ->addMessage(array('message' => "Account successfully registered."));
                    $this->_redirect('admin/list-organisation');
                }
            } catch (Exception $e) {
                print $e->getMessage();
            }

        }
        // Populate form with basic recommended default groups.
        $basic['default_fields'] = Iati_WEP_AccountDisplayFieldGroup::$defaults;
        $form->populate($basic);

        $this->view->form = $form;
        $this->view->blockManager()->disable('partial/primarymenu.phtml');
    }

    /**
     * Function to register new user for the organisation by admin user.
     */
    public function registerUserAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $adminId = $identity->user_id;
        $accountId = $identity->account_id;
        $model = new Model_Wep();
        /*
        $admindefaultField = $model->getDefaults('default_field_groups',
                                                 'account_id',
                                                 $identity->account_id
                                            );
        */
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
                    $this->_helper->FlashMessenger
                        ->addMessage(array('error' => "Username already exists."));
                    $form->populate($data);
                }
                else if (!empty($emailExists)) {
                    $this->_helper->FlashMessenger
                        ->addMessage(array('error' => "User with the email
                                           address already exists."));
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

                    $this->_helper->FlashMessenger
                        ->addMessage(array('message' => "Account successfully registered."));
                    $this->_redirect('user/user/login');
                }
            } catch (Exception $e) {
                print_r($e);exit;
            }
        }
        $this->view->form = $form;
        $this->_helper->layout()->setLayout('layout_wep');

    }

    public function listUsersAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $model = new Model_Wep();
        //print_r($identity->account_id);exit;
        $usersList = $model->getUsersByAccountId(
                                                 'user',
                                                 $identity->account_id,
                                                 array('role_id' => '2')
                                            );
        //print_r($usersList);exit;
        $this->view->users = $usersList;
        //$this->_helper->layout()->setLayout('layout_wep');
    }

    public function deleteUserAction(){
        $identity = Zend_Auth::getInstance()->getIdentity();
        if($identity->role == 'admin' || $identity->role = 'superadmin'){
            if(isset($_GET['user_id'])){
                try{
                    $model = new Model_Admin();
                    $model->deleteUserById($_GET['user_id']);
                    $this->_helper->FlashMessenger
                        ->addMessage(array('message' => 'User Deleted.'));
                    $this->_redirect('admin/list-users');
                }
                catch(Exception $e){
                 print $e->getMessage();exit;
                }
            }
        }
        else{
            $this->_helper->FlashMessenger
                ->addMessage(array('error' => 'Access Denied.'));
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
            $this->_helper->FlashMessenger
                ->addMessage(array('error' => 'Access Denied.'));
            $this->_redirect('user/user/login');

        }
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
                $defaultFieldId = $model->updateRow(
                                                    'user_permission',
                                                    $defaultFields,
                                                    'user_id',
                                                    $user_id
                                                );
                //print_r($defaultKey);exit;
                $privilegeFields['resource'] = serialize($defaultKey);
                $privilegeFields['owner_id'] = $user_id;
                $privilegeFieldId = $model->updateRow(
                                                    'Privilege',
                                                    $privilegeFields,
                                                    'owner_id',
                                                    $user_id
                                                );

                $this->_helper->FlashMessenger
                    ->addMessage(array('message' => 'User permission updated.'));
                $this->_redirect('admin/view-profile?user_id='.$user_id);
            }
            catch(Exception $e){
             print $e;
            }
        }
        $this->view->form = $form;
        if($identity->role != 'superadmin'){
            $this->view->blockManager()->enable('partial/primarymenu.phtml');
            $this->view->blockManager()->enable('partial/add-activity-menu.phtml');
            $this->view->blockManager()->enable('partial/usermgmtmenu.phtml');
        }

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
                        $this->_helper->FlashMessenger
                            ->addMessage(array('message' => 'Changed password successfully.'));

                        $this->_redirect('admin/list-users');

                    } catch (Elistxception $e) {
                        print 'Error Occured';
                        print $e->getMessage();
                    }//end of try catch
                }
            }

        }else{
            print "no user selected";
            $this->_redirect('admin/list-users');
        }
    }

    public function changeOrganisationStatusAction()
    {
        $data['id'] = $this->_getParam('org_id');
        $data['status'] = $this->_getParam('status');

        $orgModel = new Model_Wep();
        $orgModel->updateRowsToTable('account' , $data);

        $userModel = new Model_User();
        $userModel->updateStatusByAccount($data['id'] , $data['status']);

        $this->_redirect('/admin/list-organisation');
    }

    public function listHelpAction()
    {
        $modelHelp = new Model_Help();
        $helpTopics = $modelHelp->getMessagesForList();
        $this->view->helpTopics = $helpTopics;

    }

    public function editHelpMessageAction()
    {
        $eleId = $this->_getParam('id');
        if(!$eleId){
            echo "element not found" ; exit;
        }
        $helpModel = new Model_Help();
        if($this->getRequest()->isPost()){
            $formData = $this->getRequest()->getPost();
            if($eleId == 'xml_lang'){
                $helpModel->updateXmlLangMessages(stripslashes($formData['message']));
            } else if($eleId == 'iso_date'){
                $helpModel->updateDateMessages(stripslashes($formData['message']));
            } else {
                $id = $formData['id'];
                unset($formData['submit']);
                unset($formData['id']);
                $message = $formData['message'];
                $formData['message'] = stripslashes($message);
                $helpModel->updateHelpMessageById($formData , $id);
            }
            echo 'success';exit;
        }
    }

    public function changeFooterDisplayAction()
    {
        $data['id'] = $this->_getParam('org_id');
        $display = $this->_getParam('display');
        $data['display_in_footer'] =  ($display)?1:0;

        $orgModel = new Model_Wep();
        $orgModel->updateRowsToTable('account' , $data);

        $this->_helper->FlashMessenger
            ->addMessage(array('message' => 'Footer display changed sucessfully.'));
        $this->_redirect('admin/list-organisation');
    }

    public function setSimplifiedAction()
    {
        $data['id'] = $this->_getParam('org_id');
        if(!$data['id']){
            $this->_redirect('admin/list-organisation');
        }
        $isSimplified = $this->_getParam('simplified');
        $data['simplified'] =  $isSimplified;

        $orgModel = new Model_Wep();
        $orgModel->updateRowsToTable('account' , $data);

        $outMessage = ($data['simplified'])?"Organisation type changed to Simplified":
            "Organisation type changed to Default";
        $this->_helper->FlashMessenger->addMessage(array('message' => $outMessage));
        $this->_redirect('admin/list-organisation');
    }

    public function listActivityStatesAction()
    {
        $activityModel = new Model_Activity();
        $orgData = $activityModel->allOrganisationsActivityStates();
        $this->view->orgs = $orgData;
    }

    public function validateXmlFilesAction()
    {
        $xmlFolder = APPLICATION_PATH ."/../public" . Zend_Registry::get('config')->xml_folder;
        if ($handle = opendir($xmlFolder)) {
            // Loop over directory
            while (false !== ($entry = readdir($handle))) {
                // Only activities XML files
                if (!preg_match("/org/", $entry) && preg_match("/.xml/", $entry)) {
                    $activityXml[] = $entry;
                } elseif (preg_match("/org/", $entry)) {
                    $organisationXml[] = $entry;
                }
            }
            closedir($handle);
        }
        natcasesort($activityXml);
        natcasesort($organisationXml);
        $xmlForm = new Form_Admin_Xml();
        $this->view->xmlForm = $xmlForm;
        $this->view->activityXml = $activityXml;
        $this->view->organisationXml = $organisationXml;

        if($formData = $this->getRequest()->isPost()) {
            $xmlFiles = $this->getRequest()->getParam('files');
            if(empty($xmlFiles)){
                $this->_helper->FlashMessenger
                    ->addMessage(array('info' => "Please select a XML file to validate."));
                $this->_redirect('admin/validate-xml-files');
            }
            $xmlSchemaActivity = Zend_Registry::get('config')->public_folder . Zend_Registry::get('config')->xml_schema . 'iati-activities-schema.xsd'; // Schema for activity validation
            $xmlSchemaOrg = Zend_Registry::get('config')->public_folder . Zend_Registry::get('config')->xml_schema . 'iati-organisations-schema.xsd'; // Schema for organisation validation
            $xmlFiles = explode(',',$xmlFiles);
            foreach ($xmlFiles as $xml) {
                if (preg_match("/org.xml/", $xml)) {
                    $output[$xml] = shell_exec('xmllint --noout --schema ' . $xmlSchemaOrg . ' ' . $xmlFolder . escapeshellarg($xml).' 2>&1');
                } else {
                    $output[$xml] = shell_exec('xmllint --noout --schema ' . $xmlSchemaActivity . ' ' . $xmlFolder . escapeshellarg($xml).' 2>&1');
                }
                if (preg_match("/validates/", $output[$xml])) {
                    unset($output[$xml]);
                }
            }
            foreach ($output as $filename => $error) {
                $repeatString = strstr($error, $filename, true);
                if($repeatString) {
                    $error = explode($repeatString, $error);
                    if ($error[0] == '') unset($error[0]);   // unset first array
                    array_pop($error);  // unset last array
                    $output[$filename] = $error;
                }
            }
            $this->view->errors = $output;
        }
    }

    public function generatePublishedXmlFilesAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();
        $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', APPLICATION_ENV);       
        $xmlPath = $config->public_folder.$config->xml_folder;
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><iati-publishers><!-- Generated By AidStream --></iati-publishers>');
        $registryPublishedModel = new Model_RegistryPublishedData();
        $organisationRegistryPublishedModel = new Model_OrganisationRegistryPublishedData();
        $accountModel = new User_Model_DbTable_Account();
        
        $organisationRegistryPublishedData = $organisationRegistryPublishedModel->getAllOrganisationRegistryPublishedData();
        $registryPublishedData = $registryPublishedModel->getAllRegistryPublishedData();
        
        // For Activity
        $index = 1;
        foreach ($registryPublishedData as $registryData) {
            $orgName = $accountModel->getOrganisationNameById($registryData->publisher_org_id);
            $orgName = preg_replace('/&/', '&amp;', $orgName);
            if($index == 1) $registry[$orgName]['publisherId'] = substr($registryData->filename, 0, strrpos($registryData->filename, '-'));
            $registry[$orgName]['activity'][] = $registryData->filename;  
            
        }
        
        // For Organisation Data
        foreach ($organisationRegistryPublishedData as $registryData) {
            $orgName = $accountModel->getOrganisationNameById($registryData->publisher_org_id);
            $orgName = preg_replace('/&/', '&amp;', $orgName);
            if($index == 1) $registry[$orgName]['publisherId'] = substr($registryData->filename, 0, strrpos($registryData->filename, '-'));
            $registry[$orgName]['organisation'][] = $registryData->filename;
        }
        ksort($registry);
        
        foreach ($registry as $publisherName => $information) {
            $iatiPublisher = $xml->addChild('iati-publisher');
            $iatiPublisher->addChild('name', $publisherName);
            $iatiPublisher->addChild('registry-publisher-id', $information['publisherId']);
            $iatiFiles = $iatiPublisher->addChild('iati-files');
    
            if (isset($information['activity'])) {
                foreach ($information['activity'] as $iatiActivity) {
                    $fileUrl = 'http://aidstream.org/files/xml/' . trim($iatiActivity) . '.xml';
                    $iatiFile = $iatiFiles->addChild('iati-activity', $fileUrl);
                }
            }

            if (isset($information['organisation'])) {
                foreach ($information['organisation'] as $iatiOrganisation) {
                    $fileUrl = 'http://aidstream.org/files/xml/' . trim($iatiOrganisation) . '.xml';
                    $iatiFile = $iatiFiles->addChild('iati-organisation', $fileUrl);
                }
            }
        }
        
        $fileName = "published-files.xml"; 
        $fp = fopen($xmlPath.$fileName,'w');
        fwrite($fp,$xml->asXML());
        fclose($fp);
        if (file_exists($xmlPath.$fileName)) {
            $this->_redirect('/files/xml/' . $fileName);
        }
    }

    public function groupOrganisationsAction() {
        $userGroupModel = new User_Model_DbTable_UserGroup();
        $groupModel = new User_Model_DbTable_Group();
        $groups = $userGroupModel->getAllUserGroups();
        foreach ($groups as $group) {
            $groupOrganisationCount[] = $groupModel->getOrganisationCountByGroupId($group['group_id']);
        }
        $this->view->orgCount = $groupOrganisationCount;
        $this->view->groups = $groups;
    }

    public function createOrganisationGroupAction() {
        $form = new Form_Admin_CreateOrganisationGroup();
        $this->view->form = $form;
        if($this->getRequest()->isPost()){
            $data = $this->getRequest()->getPost();
            if(!$form->isValid($data)) {
                $form->populate($data);
            } else {
                $model = new Model_Wep();
                $userGroupModel = new User_Model_DbTable_UserGroup();
                $groupModel = new User_Model_DbTable_Group();

                $user['user_name'] = $data['group_identifier'] . '_group';
                $user['password'] = md5($data['password']);
                $user['role_id'] = 4;
                $user['email'] = $data['email'];
                $user['account_id'] = 0;
                $user['status'] = 1;
                $user_id = $model->insertRowsToTable('user', $user);

                $information['first_name'] = $data['first_name'];
                $information['middle_name'] = $data['middle_name'];
                $information['last_name'] = $data['last_name'];
                $information['user_id'] = $user_id;
                $profile_id = $model->insertRowsToTable('profile', $information);

                $group['name'] = $data['group_name'];
                $group['username'] = $data['group_identifier'];
                $group['user_id'] = $user_id;
                $group_id = $userGroupModel->insertUserGroup($group);
                
                $accountIds = $data['group_organisations'];
                foreach ($accountIds as $account_id) {
                    $groupModel->insertGroupWithAccountId($account_id, $group_id);
                }

                $this->_helper->FlashMessenger
                    ->addMessage(array('message' => "Organisation Group successfully created."));
                $this->_redirect('/admin/group-organisations');
            }
        }
    }

    public function editGroupAction() {
        $groupId = $this->_getParam('group_id');
        if (!isset($groupId)) {
            $this->_helper->FlashMessenger
                ->addMessage(array('error' => "No Group Id Provided."));
            $this->_redirect('/admin/group-organisations');  
        }

        $userModel = new User_Model_DbTable_User();
        $profileModel = new User_Model_DbTable_Profile();
        $userGroupModel = new User_Model_DbTable_UserGroup();
        $groupModel = new User_Model_DbTable_Group();
        
        $row = $userGroupModel->getRowByGroupId($groupId);
        if(!$row) {
            $this->_helper->FlashMessenger
                ->addMessage(array('error' => "Invalid Group Id."));
            $this->_redirect('/admin/group-organisations');
        }

        $userId = $row['user_id'];
        $row1 = $userModel->getUserById($userId);
        $row2 = $profileModel->getProfileByUserId($userId);
        $row1 = $row1->toArray();
        $row2 = $row2->toArray();
        $row3['group_organisations'] = $groupModel->getOrganisationIdByGroupId($groupId);

        $row['group_identifier'] = $row['username'];
        $row['group_name'] = $row['name'];

        $form = new Form_Admin_EditOrganisationGroup(array('user_id' => $userId));
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            if ($form->isValid($data)) {
                $user['user_name'] = $data['group_identifier'] . '_group';
                $user['email'] = $data['email'];
                $value = $userModel->updateUser($user, $userId);

                $information['first_name'] = $data['first_name'];
                $information['middle_name'] = $data['middle_name'];
                $information['last_name'] = $data['last_name'];
                $profileModel->updateProfile($information, $userId);

                $group['name'] = $data['group_name'];
                $group['username'] = $data['group_identifier'];
                $userGroupModel->updateUserGroup($group, $groupId);

                $accountIds = $data['group_organisations'];
                $groupModel->deleteGroup($groupId);
                foreach ($accountIds as $accountId) {
                    $groupModel->insertGroupWithAccountId($accountId, $groupId);
                }

                $this->_helper->FlashMessenger
                    ->addMessage(array('message' => "Organisation Group successfully updated."));
                $this->_redirect('/admin/group-organisations');                
            } else {
                $form->populate($data);
            }
        } else {
            $form->populate($row);
            $form->populate($row1);
            $form->populate($row2);
            $form->populate($row3);
        }
        
    }

    public function deleteGroupAction() {
        $groupId = $this->_getParam('group_id');
        if (!isset($groupId)) {
            $this->_helper->FlashMessenger
                ->addMessage(array('error' => "No Group Id Provided."));
            $this->_redirect('/admin/group-organisations');  
        }

        $userModel = new Model_User();
        $groupModel = new User_Model_DbTable_Group();
        $userGroupModel = new User_Model_DbTable_UserGroup();

        $row = $userGroupModel->getRowByGroupId($groupId);
        if (!$row) {
            $this->_helper->FlashMessenger
                ->addMessage(array('message' => "Cannot Delete Group. Invalid Group Id."));
            $this->_redirect('/admin/group-organisations');
        }

        // Disable User
        $data['status'] = 0;
        $userModel->updateStatusByUser($row['user_id'] , $data['status']);

        $groupModel->deleteGroup($groupId);
        $userGroupModel->deleteUserGroup($groupId);

        $this->_helper->FlashMessenger
            ->addMessage(array('message' => "Group Deleted Successfully."));
        $this->_redirect('/admin/group-organisations');

    }

}