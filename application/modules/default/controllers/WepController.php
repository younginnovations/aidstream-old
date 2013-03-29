<?php
/**
 * @todo some class description required
 * Enter description here ...
 * @author YIPL Dev team
 *
 */
class WepController extends Zend_Controller_Action
{

    //    protected $activity_id = '';
    public function init()
    {
        $identity  = Zend_Auth::getInstance()->getIdentity();
        $this->_helper->layout()->setLayout('layout_wep');
        $this->view->blockManager()->enable('partial/dashboard.phtml');
        $this->view->blockManager()->enable('partial/primarymenu.phtml');
        $this->view->blockManager()->enable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->enable('partial/published-list.phtml');
        $this->view->blockManager()->enable('partial/organisation-data.phtml');
        
        
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

        $this->view->blockManager()->enable('partial/usermgmtmenu.phtml');

    }

    public function indexAction()
    {
        //$this->view->blockManager()->disable('partial/dashboard.phtml');
    }

    public function dashboardAction()
    {
        if(Simplified_Model_Simplified::isSimplified()){
            $this->_redirect('simplified/default/dashboard');
        }
        
        $identity = Zend_Auth::getInstance()->getIdentity();
        $account_id = $identity->account_id;
        $model = new Model_Wep();
        $activityModel = new Model_Activity();

        $activities_id = $model->listAll('iati_activities', 'account_id', $account_id);
        if (empty($activities_id)) {
            $data['@version'] = '01';
            $data['@generated_datetime'] = date('Y-m-d H:i:s');
            $data['user_id'] = $identity->user_id;
            $data['account_id'] = $identity->account_id;
            $data['unqid'] = uniqid();
            $activities_id = $model->insertRowsToTable('iati_activities', $data);
        } else {
            $activities_id = $activities_id[0]['id'];
        }
        $activityCollModel = new Model_ActivityCollection();
        $activities = $activityCollModel->getActivitiesByAccount($account_id);
        $activitiesAttribs = $activityCollModel->getActivityAttribs($activities);


        $regInfoModel = new Model_RegistryInfo();
        $regInfo = $regInfoModel->getOrgRegistryInfo($account_id);

        $regPublishModel = new Model_RegistryPublishedData();
        $publishedFiles = $regPublishModel->getPublishedInfoByOrg($account_id);

        $this->view->published_data = $published_data;
        $this->view->activity_count = sizeof($activities);
        $this->view->state_count = $activityModel->getCountByState($activities);
        $this->view->last_updated_datetime = $activityModel->getLastUpdatedDatetime($activities);
        $this->view->published_activity_count = $regPublishModel->getActivityCount($publishedFiles);
        $this->view->activity_elements_info = $activitiesAttribs;
        $this->view->registry_url = Zend_Registry::get('config')->registry."../publisher/".$regInfo->publisher_id;
        $this->view->activities_id = $activities_id;

    }

    public function listActivitiesAction()
    { 
        //@todo list only activities related to the user
        if ($_GET) {
            if ($this->getRequest()->getParam('type')) {
                $tblName = $this->getRequest()->getParam('type');
            }
            /* if($identity->role == 'admin'){
             $field = 'account_id';
             }
             else{
             $field = 'user_id';
             }

             $field_data = $this->getRequest()->getParam('id'); */
            if ($this->getRequest()->getParam('account_id')) {
                $field = 'account_id';
                //print $field;exit();
                $id = $this->getRequest()->getParam('account_id');
            }
            if ($this->getRequest()->getParam('user_id')) {
                $field = 'user_id';
                $id = $this->getRequest()->getParam('user_id');
            }

            $model = new Model_Wep();
            $rowSet = $model->listAll($tblName, $field, $id);
            $this->view->rowSet = $rowSet;
        }

    }

    public function editDefaultsAction()
    {
        if(Simplified_Model_Simplified::isSimplified()){
            $this->_redirect('simplified/default/edit-defaults');
        }
        $identity = Zend_Auth::getInstance()->getIdentity();
        $model = new Model_Wep();
        $modelRegistryInfo = new Model_RegistryInfo();

        $defaultFieldsValues = $model->getDefaults('default_field_values', 'account_id', $identity->account_id);
        $default['field_values'] = $defaultFieldsValues->getDefaultFields();
        $defaultFieldGroup = $model->getDefaults('default_field_groups', 'account_id', $identity->account_id);
        $default['fields'] = $defaultFieldGroup->getProperties();
        $form = new Form_Wep_EditDefaults();
        $form->edit($default);

        $registryInfoData = $modelRegistryInfo->getOrgRegistryInfo($identity->account_id);
        if($registryInfoData){
            $form->populate($registryInfoData->toArray());
        }
        if ($_POST) {
            try {
                $data = $this->getRequest()->getPost();
                if (!$form->isValid($data)) {
                    $form->populate($data);
                } else {

                    //Update Publishing Info
                    $registryInfo = array();
                    $registryInfo['publisher_id'] = $data['publisher_id'];
                    $registryInfo['api_key'] = $data['api_key'];
                    $registryInfo['publishing_type'] = $data['publishing_type'][0];
                    $registryInfo['update_registry'] = $data['update_registry'];
                    $registryInfo['org_id'] = $identity->account_id;
                    $modelRegistryInfo->updateRegistryInfo($registryInfo);

                    //Update Default Values
                    $defaultFieldsValuesObj = new Iati_WEP_AccountDefaultFieldValues();
                    $defaultFieldGroupObj = new Iati_WEP_AccountDisplayFieldGroup();

                    $defaultFieldsValuesObj->setLanguage($data['default_language']);
                    $defaultFieldsValuesObj->setCurrency($data['default_currency']);
                    $defaultFieldsValuesObj->setReportingOrg($data['default_reporting_org']);
                    $defaultFieldsValuesObj->setHierarchy($data['hierarchy']);
                    $defaultFieldsValuesObj->setReportingOrgRef($data['reporting_org_ref']);
                    $defaultFieldsValuesObj->setReportingOrgType($data['reporting_org_type']);
                    $defaultFieldsValuesObj->setReportingOrgLang($data['reporting_org_lang']);
                    $defaultFieldsValuesObj->setCollaborationType($data['default_collaboration_type']);
                    $defaultFieldsValuesObj->setFlowType($data['default_flow_type']);
                    $defaultFieldsValuesObj->setFinanceType($data['default_finance_type']);
                    $defaultFieldsValuesObj->setAidType($data['default_aid_type']);
                    $defaultFieldsValuesObj->setTiedStatus($data['default_tied_status']);

                    $fieldString = serialize($defaultFieldsValuesObj);

                    $defaultValues['id'] = $model->getIdByField('default_field_values', 'account_id', $identity->account_id);
                    $defaultValues['object'] = $fieldString;
                    $defaultValuesId = $model->updateRowsToTable('default_field_values', $defaultValues);

                    //Update Default Fields
                    foreach ($data['default_fields'] as $eachField) {
                        $defaultFieldGroupObj->setProperties($eachField);
                    }

                    $fieldString = serialize($defaultFieldGroupObj);
                    $defaultFields['id'] = $model->getIdByField('default_field_groups', 'account_id', $identity->account_id);
                    $defaultFields['object'] = $fieldString;
                    $defaultFieldId = $model->updateRowsToTable('default_field_groups', $defaultFields);

                    $this->_helper->FlashMessenger->addMessage(array('message' => "Defaults successfully updated."));
                    if ($identity->role == 'superadmin') {
                        $this->_redirect('admin/dashboard');
                    } else if ($identity->role == 'admin') {
                        $this->_redirect('wep/dashboard');
                    }
                }
            } catch (Exception $e) {
                print $e;
            }
        }
        $this->view->blockManager()->enable('partial/dashboard.phtml');
        $this->view->form = $form;
    }

    public function addActivitiesAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $form = new Form_Wep_IatiActivities();
        $form->add();
        if ($this->getRequest()->isPost()) {
            try {
                $data = $this->getRequest()->getPost();
                if (!$form->isValid($data)) {
                    $form->populate($data);
                } else {
                    $wepModel = new Model_Wep();

                    $data1['@version'] = $this->_request->getParam('version');
                    $data1['@generated_datetime'] = $this->_request->getParam('generated_datetime');
                    $data1['unqid'] = uniqid();
                    $data1['user_id'] = $identity->user_id;
                    $data1['account_id'] = $identity->account_id;

                    $activities_id = $wepModel->insertRowsToTable('iati_activities', $data1);
                    $this->_helper->FlashMessenger->addMessage(array('message' => "Activities Saved."));

                    $this->_redirect('wep/list-activities?account_id=' . $identity->account_id . '&type=iati_activities');
                }
            } catch (Exception $e) {
                print $e;
            }
        }

        $this->view->form = $form;
        $this->view->blockManager()->enable('partial/dashboard.phtml');
    }

    public function addActivityAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        if ($_GET) {
            $activities_id = $this->getRequest()->getParam('activities_id');
            $wepModel = new Model_Wep();
            $exists = $wepModel->getRowById('iati_activities', 'id', $_GET['activities_id']);
            if(!$exists){
                $this->_helper->FlashMessenger->addMessage(array('message' => "Invalid Id."));
                $this->_redirect('/user/user/login');
            }
        }
        else{
            $wepModel = new Model_Wep();
            $activities = $wepModel->listAll('iati_activities', 'account_id', $identity->account_id);
            $activities_id = $activities[0]['id'];
        }
        
        $model = new Model_Viewcode();
        $rowSet = $model->getRowsByFields('default_field_values' , 'account_id' , $identity->account_id);

        $defaultValues = unserialize($rowSet[0]['object']);
        $default = $defaultValues->getDefaultFields();
        $wepModel = new Model_Wep();

        $activity_info['@xml_lang'] = $wepModel->fetchValueById('Language' , $default['language'] , 'Code');
        $activity_info['@default_currency'] = $wepModel->fetchValueById('Currency' , $default['currency'] , 'Code');
        $activity_info['@hierarchy'] = $default['hierarchy'];
        $activity_info['@last_updated_datetime'] = date('Y-m-d H:i:s');
        $activity_info['activities_id'] = $activities_id;

        $reporting_org_info['@reporting_org_name'] = $default['reporting_org'];
        $reporting_org_info['@reporting_org_ref'] = $default['reporting_org_ref'];
        $reporting_org_info['@reporting_org_type'] = $wepModel->fetchValueById('OrganisationType' , $default['reporting_org_type'] , 'Code');
        $reporting_org_info['@reporting_org_lang'] = $wepModel->fetchValueById('Language' , $default['reporting_org_lang'] , 'Name');
        $incomplete = false;
        foreach($reporting_org_info as $key => $reportingOrgValue){
            if(!$reportingOrgValue && $key != '@reporting_org_lang'){
                $incomplete = true;
                break;
            }
        }
        if($incomplete){
            //For admin user redirect to defaults page.
            if($identity->role_id == 1){
                $this->_helper->FlashMessenger->addMessage(array(
                                                                 'message' => "Before you start entering activity data
                                                                 you need to add some default values that will
                                                                 automatically be filled in for
                                                                 each activity you report."
                                                                   )
                                                           );
                $this->_redirect('wep/edit-defaults');
            } else { // For other user redirect to dashboard.
                $this->_helper->FlashMessenger->addMessage(array(
                                                                 'message' => "All information for Reporting Organisation
                                                                    is not provided .Please contact you organisation admin"
                                                                  )
                                                           );
                $this->_redirect('wep/dashborad');
            }
        }

        $activityDefaults['@collaboration_type'] = $wepModel->fetchValueById('CollaborationType' , $default['collaboration_type'] , 'Code');
        $activityDefaults['@flow_type'] = $wepModel->fetchValueById('FlowType' , $default['flow_type'] , 'Code');
        $activityDefaults['@finance_type'] = $wepModel->fetchValueById('FinanceType' , $default['finance_type'] , 'Code');
        $activityDefaults['@aid_type'] = $wepModel->fetchValueById('AidType' , $default['aid_type'] , 'Code');
        $activityDefaults['@tied_status'] = $wepModel->fetchValueById('TiedStatus' , $default['tied_status'] , 'Code');

        $form = new Form_Wep_IatiIdentifier('add', $identity->account_id);
        $form->add('add', $identity->account_id);
        $form->populate(array('reporting_org'=>$default['reporting_org_ref']));

        if ($_POST) {
            try {
                $data = $this->getRequest()->getPost();
                if (!$form->isValid($data)) {
                    $form->populate($data);
                } else {
                    $iatiIdentifier = array();
                    $iatiIdentifier['iati_identifier'] = $data['iati_identifier_text'];
                    $iatiIdentifier['activity_identifier'] = $data['activity_identifier'];

                    $activityModel = new Model_Activity();
                    $activity_id = $activityModel->createActivity($activities_id , $default , $iatiIdentifier);

                    //Create Activity Hash
                    $activityHashModel = new Model_ActivityHash();
                    $updated = $activityHashModel->updateHash($activity_id);

                    $this->_helper->FlashMessenger->addMessage(array('message' => "Activity Sucessfully Created."));
                    $this->_redirect('wep/view-activity/' . $activity_id);
                }
            } catch (Exception $e) {
                print $e;
            }
        }

        $this->view->activities_id = $activities_id;
        $this->view->activity_info = $activity_info;
        $this->view->reporting_org_info = $reporting_org_info;
        $this->view->activityDefaults = $activityDefaults;
        $this->view->form = $form;
    }

    public function getInitialValues($activity_id, $class)
    {
        $refArray = array(
            'ReportingOrg', 'ParticipatingOrg', 'Transaction'

        );
        $identity = Zend_Auth::getInstance()->getIdentity();
        $model = new Model_Wep();
        $defaultFieldValues = $model->getDefaults('default_field_values', 'account_id', $identity->account_id);
        $defaults = $defaultFieldValues->getDefaultFields();
        $initial['@currency'] = $defaults['currency'];
        //$initial['@xml_lang'] = $defaults['language'];
        $initial['text'] = '';
        if ($class == 'ReportingOrg') {
            $initial['text'] = $defaults['reporting_org'];
        }
        if ($class == 'ReportingOrg') {
            $initial['@ref'] = $defaults['reporting_org_ref'];
        }
        if ($class == 'OtherActivityIdentifier') {
            $initial['@owner_ref'] = $defaults['reporting_org_ref'];
        }
        if ($class == 'ReportingOrg') {
            $initial['@ref'] = $defaults['reporting_org_ref'];
        }
        if ($class == 'Transaction') {
            $initial['@ref'] = $defaults['reporting_org_ref'];
        }
        return $initial;
    }

    public function createGlobalObject($activity_id, $class)
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $classname = 'Iati_WEP_Activity_Elements_'. $class;
        $globalobj = new $classname();
        //        $globalobj->setAccountActivity(array('account_id'=>$identity->account_id, 'activity_id'=>$activity_id));
        $globalobj->propertySetter(array('activity_id' => $activity_id));
        return $globalobj;
    }

    public function addToRegistry($object, $parent = NULL)
    {
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        $registryTree->addNode($object, $parent);
        return $registryTree;
    }

    public function addActivityElementsAction ()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $model = new Model_Wep();

        $id = null;
        if ($_GET['class']) {
            $class = $this->_request->getParam('class');
        }
        if ($_GET['activity_id']) {
            $activity_id = $this->_request->getParam('activity_id');
            $activity_info = $model->listAll('iati_activity', 'id', $activity_id);
            if (empty($activity_info)) {
                //@todo
            }
            $activity = $activity_info[0];
            $activity['@xml_lang'] = $model->fetchValueById('Language',$activity_info[0]['@xml_lang'], 'Code');
            $activity['@default_currency'] = $model->fetchValueById('Currency',
            $activity_info[0]['@default_currency'], 'Code');
            $iati_identifier_row = $model->getRowById('iati_identifier', 'activity_id', $activity_id);
            $activity['iati_identifier'] = $iati_identifier_row['text'];
            $activity['activity_identifier'] = $iati_identifier_row['activity_identifier'];
            $title_row = $model->getRowById('iati_title', 'activity_id', $activity_id);
            $activity['iati_title'] = $title_row['text'];
        }
        $this->view->activityInfo = $activity;
        $initial = $this->getInitialValues($activity_id, $class);
        $classname = 'Iati_WEP_Activity_'. $class . 'Factory';
        if(isset($class)){
            try{
                if($_POST){
                    //var_dump($_POST);
                    $flatArray = $this->flatArray($_POST);
                    //echo "<pre>";
                    //print_r($flatArray);exit;
                    $activity = new Iati_WEP_Activity_Elements_Activity();
                    $activity->setAttributes(array('activity_id' => $activity_id));
                    $registryTree = Iati_WEP_TreeRegistry::getInstance();
                    $registryTree->addNode($activity);
                    $factory = new $classname ();
                    $factory->setInitialValues($initial);
                    $tree = $factory->factory($class, $flatArray);
                    $factory->validateAll($activity);
                    if($factory->hasError()){
                        $formHelper = new Iati_WEP_FormHelper();
                        $a = $formHelper->getForm();
                        $this->_helper->FlashMessenger->addMessage(array('error' => "You have some problem in your data. Please correct and save again"));
                    } else {
                        if(!$this->hasData($flatArray)){
                             $this->_helper->FlashMessenger->addMessage(array('message' => "You have not entered any data."));
                            $this->_redirect('wep/add-activity-elements/?activity_id='.$activity_id.'&class='.$class);
                        }
                        $elementClassName = 'Iati_Activity_Element_Activity';
                        $element = new $elementClassName ();
                        $data = $activity->getCleanedData();

                        $element->setAttribs($data);
                        $factory = new $classname ();
                        $activityTree = $factory->cleanData($activity, $element);
                        //print_r($activityTree);exit;
                        $dbLayer = new Iati_WEP_DbLayer();
                        $dbLayer->save($activityTree);

                        //update the activity so that the last updated time is updated
                        $this->updateActivityUpdatedDatetime($activity_id);

                        $camelCaseToSeperator = new Zend_Filter_Word_CamelCaseToSeparator(" ");
                        $title = $camelCaseToSeperator->filter($class);

                        $activityHashModel = new Model_ActivityHash();
                        $updated = $activityHashModel->updateHash($activity_id);

                        //change state to editing
                        $db = new Model_ActivityStatus;
                        $db->updateActivityStatus($activity_id,Iati_WEP_ActivityState::STATUS_EDITING);

                        if($_POST['save'] == 'Save and View'){
                            $this->_helper->FlashMessenger
                            ->addMessage(array('message' => "$title successfully inserted."));
                            $this->_redirect("/wep/view-activity/".$activity_id);
                        }else{
                            $this->_helper->FlashMessenger
                            ->addMessage(array('message' => "$title successfully inserted."));
                            $this->_redirect("/wep/edit-activity-elements?activity_id=".
                                             $activity_id . "&class=" . $class);
                        }

                    }
                    /*
                     $formHelper = new Iati_WEP_FormHelper();
                     $a = $formHelper->getForm();*/
                }
                else{

                    $activity = new Iati_WEP_Activity_Elements_Activity();
                    $activity->setAttributes(array('activity_id' => $activity_id));

                    $registryTree = Iati_WEP_TreeRegistry::getInstance();
                    $registryTree->addNode($activity);

                    $factory = new $classname();
                    $factory->setInitialValues($initial);

                    $tree = $factory->factory($class);
                    $formHelper = new Iati_WEP_FormHelper();
                    $a = $formHelper->getForm();

                }
            }
            catch (Exception $e){
                //print_r($e->getMessage());exit;
            }
        }
        $this->view->form = $a;

        $this->view->blockManager()->enable('partial/activitymenu.phtml');
        $this->view->blockManager()->disable('partial/primarymenu.phtml');
        $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->disable('partial/usermgmtmenu.phtml');
        $this->view->blockManager()->disable('partial/published-list.phtml');
        $this->view->blockManager()->disable('partial/organisation-data.phtml');        
    }

    public function editActivityElementsAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $model = new Model_Wep();
        $id = null;
        if ($_GET['class']) {
            $class = $this->_request->getParam('class');
            $className = 'Iati_WEP_Activity_Elements_'.$class;
            $classObj = new $className ();
            $displayName = $classObj->getElementDisplayName();
            $camelCaseToSeperator = new Zend_Filter_Word_CamelCaseToSeparator(" ");
            $title = $camelCaseToSeperator->filter($displayName);
        }
        if ($_GET['activity_id']) {
            $exists = $model->getRowById('iati_activity', 'id', $_GET['activity_id']);
            if(!$exists){
                $this->_helper->FlashMessenger->addMessage(array('warning' => "Activity does not exist."));

                $this->_redirect('/user/user/login');
            }
            $activity_id = $this->_request->getParam('activity_id');
            $activity_info = $model->listAll('iati_activity', 'id', $activity_id);
            if (empty($activity_info)) {
                //@todo
            }
            $activity = $activity_info[0];
            $activity['@xml_lang'] = $model->fetchValueById('Language', $activity_info[0]['@xml_lang'], 'Code');

            $activity['@default_currency'] = $model->fetchValueById('Currency', $activity_info[0]['@default_currency'], 'Code');

            $iati_identifier_row = $model->getRowById('iati_identifier', 'activity_id', $activity_id);
            $activity['iati_identifier'] = $iati_identifier_row['text'];
            $activity['activity_identifier'] = $iati_identifier_row['activity_identifier'];
            $title_row = $model->getRowById('iati_title', 'activity_id', $activity_id);
            $activity['iati_title'] = $title_row['text'];
        }

        $this->view->activityInfo = $activity;
        $initial = $this->getInitialValues($activity_id, $class);
        $classname = 'Iati_WEP_Activity_'. $class . 'Factory';
        if(isset($class)){

            if($_POST){
                $flatArray = $this->flatArray($_POST);

                $activity = new Iati_WEP_Activity_Elements_Activity();
                $activity->setAttributes(array('activity_id' => $activity_id));
                $registryTree = Iati_WEP_TreeRegistry::getInstance();
                $registryTree->addNode($activity);
                $factory = new $classname ();

                $factory->setInitialValues($initial);
                $tree = $factory->factory($class, $flatArray);

                $factory->validateAll($activity);

                if($factory->hasError()){
                    $formHelper = new Iati_WEP_FormHelper();
                    $a = $formHelper->getForm();
                    $this->_helper->FlashMessenger->addMessage(array('error' => "You have some problem in your data. Please correct and save again"));
                }
                else{
                    if(!$this->hasData($flatArray)){
                        $this->_helper->FlashMessenger->addMessage(array('info' => "You have not entered any data."));
                        $this->_redirect('wep/add-activity-elements/?activity_id='.$activity_id.'&class='.$class);
                    }
                    $elementClassName = 'Iati_Activity_Element_Activity';
                    $element = new $elementClassName ();
                    $data = $activity->getCleanedData();

                    $element->setAttribs($data);
                    $factory = new $classname ();
                    $activityTree = $factory->cleanData($activity, $element);
                    //print_r($activityTree);exit;
                    $dbLayer = new Iati_WEP_DbLayer();
                    $dbLayer->save($activityTree);

                    //Update Activity Hash
                    $activityHashModel = new Model_ActivityHash();
                    $updated = $activityHashModel->updateHash($activity_id);
                    if(!$updated){
                        $type = 'info';
                        $message = 'No Changes Made';
                    } else {
                        //update the activity so that the last updated time is updated
                        $this->updateActivityUpdatedDatetime($activity_id);

                        //change state to editing
                        $db = new Model_ActivityStatus;
                        $db->updateActivityStatus($activity_id,Iati_WEP_ActivityState::STATUS_EDITING);
                        $type = 'message';
                        $message = "$title successfully updated.";
                    }
                    $this->_helper->FlashMessenger
                            ->addMessage(array($type => $message));

                    if($_POST['save'] == 'Save and View'){
                            $this->_redirect("/wep/view-activity/".$activity_id);
                        }else{
                            $this->_redirect("/wep/edit-activity-elements?activity_id=".
                                             $activity_id . "&class=" . $class);
                    }
                }
            }
            else{
                $dbLayer = new Iati_WEP_DbLayer();
                $rowSet = $dbLayer->getRowSet($class, 'activity_id', $activity_id, true);
                $elements = $rowSet->getElements();
                $attributes = $elements[0]->getAttribs();
                if(empty($attributes)){
                    //$this->_helper->FlashMessenger->addMessage(array('message' => "$title not found for this activity. Please add $title."));
                    $this->_redirect("wep/add-activity-elements/?activity_id=".$activity_id."&class=".$class);
                }

                $registryTree = Iati_WEP_TreeRegistry::getInstance();

                $factory = new $classname;
                $factory->setInitialValues($initial);
                $tree = $factory->extractData($rowSet, $activity_id);

                $formHelper = new Iati_WEP_FormHelper();
                $a = $formHelper->getForm();
            }
        }

        $this->view->blockManager()->enable('partial/activitymenu.phtml');
        $this->view->blockManager()->disable('partial/primarymenu.phtml');
        $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->disable('partial/usermgmtmenu.phtml');
        $this->view->blockManager()->disable('partial/published-list.phtml');
        $this->view->blockManager()->disable('partial/organisation-data.phtml');

        $this->view->form = $a;
    }

    public function cloneNodeAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        // Treating transaction and result elements seperately
        if(isset($_GET['class'])){

            $class = $this->_getParam('class');
            $refItem = $this->_getParam('refEle');
            $reg = "/{$class}-\d+/";
            preg_match( $reg , $refItem ,  $matches );
            $item = substr($matches[0] , -1);
            $refItem = preg_replace("/$class.*$/" , '' ,  $refItem);
            $refItem = rtrim($refItem , '-');
            $parents = array();
            preg_match_all('/\w+-\d+/' , $refItem , $parents);
            if(!empty($parents[0])){
                //Factory classes are present in the activity directory but for lower level
                //elements they are present inside the element directory or the activity directory
                $parent = preg_replace('/-\d+/', '', $refItem);
                $parent = preg_replace('/-/' , '_', $parent);
                $classname = 'Iati_WEP_Activity_' . $parent . 'Factory';
                if(!class_exists($classname)){
                    $classname = 'Iati_WEP_Activity_Elements_' . $parent . 'Factory';
                }
            } else {
                $classname = 'Iati_WEP_Activity_' . $class . 'Factory';
            }
            //array_push($parents , $class);
            $factory = new $classname;
            $initial = $this->getInitialValues('', $class);
            $factory->setInitialValues($initial);
            $tree = $factory->factory($class);
            $item++;

            $formHelper = new Iati_WEP_FormHelper();
            $a = $formHelper->getFormWithAjax($parents[0], $item);
            $partialPath = Zend_Registry::get('config')->resources->layout->layoutpath;
            $myView = new Zend_View;
            $myView->setScriptPath($partialPath.'/partial');
            $myView->assign('form' , $a);
            $form = $myView->render('form.phtml');
            print $form;
            exit;

        } else {
            if($_GET['classname'])
            {
                $class = $_GET['classname'];
            }
            $initial = $this->getInitialValues($activity_id, $class);
            $parents = array();
            $items = array();
            $parentExp = "/^parent/";
            $itemExp = "/^item/";
            foreach($_GET as $key => $eachValue){
                if(preg_match($parentExp, $key)){
                    $a = explode('parent', $key);
                    $parents[$a[1]] = $eachValue;
                }
                if(preg_match($itemExp, $key)){
                    $a = explode('item', $key);
                    $items[$a[1]] = $eachValue;
                }
            }
            $class1 = (isset($parents[0]))?$parents[0]:$class;
            $classname = 'Iati_WEP_Activity_' . $class1 . 'Factory';
            $factory = new $classname;
            $factory->setInitialValues($initial);
            $tree = $factory->factory($class);

            array_push($parents, $class);
            $formHelper = new Iati_WEP_FormHelper();
            $a = $formHelper->getFormWithAjax($parents, $items);
            print $a;
            exit;
        }
        $this->_helper->layout->disableLayout();
        //     $this->_helper->viewRenderer->setNoRender(true);
    }

    public function viewActivitiesAction()
    { 
        if(Simplified_Model_Simplified::isSimplified()){
            $this->_redirect('simplified/default/view-activities');
        }
        
        $identity = Zend_Auth::getInstance()->getIdentity();
        $wepModel = new Model_Wep();

        if ($_GET) {
            $activities_id = $this->getRequest()->getParam('activities_id');
            $wepModel = new Model_Wep();
            $exists = $wepModel->getRowById('iati_activities', 'id', $_GET['activities_id']);
            if(!$exists){
                $this->_helper->FlashMessenger->addMessage(array('error' => "Invalid Id."));

                $this->_redirect('/user/user/login');
            }
        }
        else{
            $activities = $wepModel->listAll('iati_activities', 'account_id', $identity->account_id);
            $activities_id = $activities[0]['id'];
        }

        $this->view->activities_id = $activities_id;
        $activityArray = $wepModel->listAll('iati_activity', 'activities_id', $activities_id);

        //foreach activity get activity title
        $titleArray = array();
        if ($activityArray) {
            $i = 0;
            foreach($activityArray as $key=>$activity){

                $title = $wepModel->listAll('iati_title', 'activity_id', $activity['id']);
                $identifier = $wepModel->listAll('iati_identifier', 'activity_id', $activity['id']);
                //                    print_r($title[0]['text']);exit;
                $activity_array[$i]['title'] = ($title[0]['text'])?$title[0]['text']:'No title';
                $activity_array[$i]['identifier'] = ($identifier[0]['activity_identifier'])?$identifier[0]['activity_identifier']:'No Activity Identifier';
                $activity_array[$i]['last_updated_datetime'] = $activity['@last_updated_datetime'];
                $activity_array[$i]['id'] = $activity['id'];
                $activity_array[$i]['status_id']  = $activity['status_id'];
                $i++;
            }
        }
        if(Iati_WEP_ActivityState::hasPermissionForState(Iati_WEP_ActivityState::STATUS_PUBLISHED)){
            $modelRegistryInfo = new Model_RegistryInfo();
	        $registryInfo = $modelRegistryInfo->getOrgRegistryInfo($identity->account_id);

	        $status_form = new Form_Wep_ActivityStatus();
            if($registryInfo->update_registry){
                $status_form->change->setLabel('Publish and Register');
            } else {
                $status_form->change->setLabel('Publish');
            }
            $status_form->change->setAttrib('id','publish');
            $status_form->status->setValue(Iati_WEP_ActivityState::STATUS_PUBLISHED);
            $status_form->setAction($this->view->baseUrl()."/wep/update-status");
        }

        $this->view->activity_array = $activity_array;
        $this->view->status_form = $status_form;
    }

    public function viewActivityAction()
    { 
        if(!$activity_id = $this->getRequest()->getParam('activity_id'))
        {
            $this->_redirect('/wep/view-activities');
        }

        $identity = Zend_Auth::getInstance()->getIdentity();
        $model = new Model_Wep();
        $activity_info = $model->listAll('iati_activity', 'id', $activity_id);
        $activity = $activity_info[0];
        $state = $activity['status_id'];
        $activity['@xml_lang'] = $model->fetchValueById('Language', $activity_info[0]['@xml_lang'], 'Code');
        
        $activity['@default_currency'] = $model->fetchValueById('Currency', $activity_info[0]['@default_currency'], 'Code');

        $iati_identifier_row = $model->getRowById('iati_identifier', 'activity_id', $activity_id);
        $activity['activity_identifier'] = $iati_identifier_row['activity_identifier'];
        $title_row = $model->getRowById('iati_title', 'activity_id', $activity_id);
        $activity['iati_title'] = $title_row['text'];

        // Get form for status change
        $next_state = Iati_WEP_ActivityState::getNextStatus($state);
        if($next_state && Iati_WEP_ActivityState::hasPermissionForState($next_state)){
            $status_form = new Form_Wep_ActivityChangeState();
            $status_form->setAction($this->view->baseUrl()."/wep/update-status");
            $status_form->ids->setValue($activity_id);
            $status_form->status->setValue($next_state);
            $status_form->change_state->setLabel(Iati_WEP_ActivityState::getStatus($next_state));
        } else {
            $status_form = null;
        }
        
        // Fetch activity data
        $activityClassObj = new Iati_Aidstream_Element_Activity();
        $activityData = $activityClassObj->fetchData($activity_id , false);

        $dbLayer = new Iati_WEP_DbLayer();
        $activitys = $dbLayer->getRowSet('Activity', 'id', $activity_id, true, true);
        $output = '';
        
        $this->view->activityData = $activityData;
        
        $this->view->activity = $activitys;

        $this->view->status_form = $status_form;
        $this->view->state = $state;
        $this->view->activityInfo = $activity;
        $this->view->activity_id = $activity_id;

        $this->view->blockManager()->enable('partial/activitymenu.phtml');
        $this->view->blockManager()->disable('partial/primarymenu.phtml');
        $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->disable('partial/usermgmtmenu.phtml');
        $this->view->blockManager()->disable('partial/published-list.phtml');
        $this->view->blockManager()->disable('partial/organisation-data.phtml');
    }

    public function editActivityAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        if ($_GET) {
            $wepModel = new Model_Wep();
            if(isset($_GET['activities_id'])){ 
                $exists = $wepModel->getRowById('iati_activities', 'id', $_GET['activities_id']);
                if(!$exists){
                    $this->_helper->FlashMessenger->addMessage(array('message' => "Activities does not exist."));

                    $this->_redirect('/user/user/login');
                }

                $activities_id = $this->getRequest()->getParam('activities_id');
                $rowSet = $wepModel->getRowsByFields('default_field_values', 'account_id', $identity->account_id);
                $defaultValues = unserialize($rowSet[0]['object']);
                $default = $defaultValues->getDefaultFields();
                $activity['xml_lang'] = $default['language'];
                $activity['default_currency'] = $default['currency'];
                $activity['hierarchy'] = $default['hierarchy'];
                $form = new Form_Wep_IatiActivity();
                $form->add('add', $identity->account_id);
                $form->populate(array('reporting_org'=>$default['reporting_org_ref']));

            }
            if(isset($_GET['activity_id'])){
                $exists = $wepModel->getRowById('iati_activity', 'id', $_GET['activity_id']);
                if(!$exists){
                    $this->_helper->FlashMessenger->addMessage(array('warning' => "Activity does not exist."));

                    $this->_redirect('/user/user/login');
                }
                $activity_id = $this->getRequest()->getParam('activity_id');
                $rowSet = $wepModel->getRowsByFields('iati_activity', 'id', $activity_id);
                $activity['xml_lang'] = $rowSet[0]['@xml_lang'];
                $activity['default_currency'] = $rowSet[0]['@default_currency'];
                $activity['hierarchy'] = $rowSet[0]['@hierarchy'];
                $activity['activities_id'] = $rowSet[0]['activities_id'];
                $form = new Form_Wep_EditIatiActivity();
                $form->edit($identity->account_id);


                $aActivityInfo = $wepModel->listAll('iati_activity', 'id', $activity_id);
                $activityInfo = $aActivityInfo[0];
                $iati_identifier_row = $wepModel->getRowById('iati_identifier', 'activity_id', $activity_id);
                $activityInfo['iati_identifier'] = $iati_identifier_row['text'];
                $title_row = $wepModel->getRowById('iati_title', 'activity_id', $activity_id);
                $activityInfo['iati_title'] = $title_row['text'];

                $this->view->activityInfo = $activityInfo;
            }

            if ($this->getRequest()->isPost()) {
                $formData = $this->getRequest()->getPost();
                if (!$form->isValid($formData)) {

                    $form->populate($formData);
                } else {
                    if(isset($_GET['activities_id'])){

                        $default['language'] = $formData['xml_lang'];
                        $default['currency'] = $formData['default_currency'];
                        $default['hierarchy'] = $formData['hierarchy'];

                        $iatiIdentifier = array();
                        $iatiIdentifier['iati_identifier'] = $formData['iati_identifier_text'];
                        $iatiIdentifier['activity_identifier'] = $formData['activity_identifier'];

                        $activityModel = new Model_Activity();
                        $activityId = $activityModel->createActivity($activities_id , $default , $iatiIdentifier);

                        //Create Activity Hash
                        $activityHashModel = new Model_ActivityHash();
                        $updated = $activityHashModel->updateHash($activityId);

                        $this->_helper->FlashMessenger->addMessage(array('message' => "Activity Sucessfully Created."));
                        $this->_redirect('wep/view-activity/' . $activityId);
                    }

                    if(isset($_GET['activity_id'])){
                        //$data['activities_id'] = $rowSet[0]['activities_id'];
                        $data['id'] = $activity_id;
                        $data['@xml_lang'] = $formData['xml_lang'];
                        $data['@default_currency'] = $formData['default_currency'];
                        $data['@hierarchy'] = $formData['hierarchy'];
                        $result = $wepModel->updateRowsToTable('iati_activity', $data);
                        $wepModel = new Model_Wep();
                        $activityHashModel = new Model_ActivityHash();
                        $updated = $activityHashModel->updateHash($activity_id);
                        if(!$updated){
                            $type = 'info';
                            $message = 'No Changes Made';
                        } else {
                            //change state to editing
                            $db = new Model_ActivityStatus;
                            $db->updateActivityStatus($activity_id,Iati_WEP_ActivityState::STATUS_EDITING);
                            $type = 'message';
                            $message = "Activity overridden";
                        }
                        $this->_helper->FlashMessenger
                                ->addMessage(array($type => $message));
                    }
                    $this->_redirect('wep/view-activity/' . $activity_id);
                }//end of inner if
            } else {

                $form->populate($activity);

            }

            $this->view->form = $form;
            $this->view->activity_id = $activity_id;
        }

    }
    public function overrideActivityAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        if ($_GET) {
            $activity_id = $this->getRequest()->getParam('activity_id');

            $wepModel = new Model_Wep();

            $activity_info = $wepModel->listAll('iati_activity', 'id', $activity_id);
            $activity['xml_lang'] = $activity_info[0]['@xml_lang'];
            $activity['default_currency'] = $activity_info[0]['@default_currency'];
            $activity['hierarchy'] = $activity_info[0]['@hierarchy'];
            $activity['last_updated_datetime'] = $activity_info[0]['@last_updated_datetime'];
            $activity['activities_id'] = $activity_info[0]['activities_id'];

            $form = new Form_Wep_IatiActivity();
            $form->add('edit', $activity_info[0]['activities_id'], $identity->account_id);

            if ($this->getRequest()->isPost()) {
                $formData = $this->getRequest()->getPost();
                if (!$form->isValid($formData)) {

                    $form->populate($formData);
                } else {

                    $data['id'] = $activity_info[0]['id'];
                    $data['@xml_lang'] = $formData['xml_lang'];
                    $data['@default_currency'] = $formData['default_currency'];
                    $data['@hierarchy'] = $formData['hierarchy'];
                    $data['@last_updated_datetime'] = date('Y-m-d H:i:s');
                    $data['activities_id'] = $formData['activities_id'];
                    $activity_id = $wepModel->updateRowsToTable('iati_activity', $data);

                    $this->_redirect('wep/view-activities/?activities_id=' . $data['activities_id']);
                }//end of inner if
            } else {

                $form->populate($activity);

            }

            $this->view->form = $form;

        }
    }

    public function removeElementsAction()
    {
        $this->_helper->layout->disableLayout();
        if($this->_request->isGet()){
            try{
                if($_GET['classname'])
                {
                    $class = $_GET['classname'];
                }

                if($_GET['id']){
                    $id = $_GET['id'];
                }
                $parents = array();
                $items = array();
                $parentExp = "/^parent/";
                foreach($_GET as $key => $eachValue){
                    if(preg_match($parentExp, $key)){
                        $a = explode('parent', $key);
                        $parents[$a[1]] = $eachValue;
                    }
                }

                $class1 = (isset($parents[0]))?$parents[0]. "_" . $class:$class;
                $fieldName = 'id';
                $value = $id;
                $dbLayer = new Iati_WEP_DbLayer();
                $del = $dbLayer->deleteRows($class1, $fieldName, $value);
                print 'success';
                exit();

            } catch(Exception $e){
                print $e; exit();
            }
        }
    }

    /**
     * @deprecated
     */
    public function deleteAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        if ($_GET) {
            $id = $this->_request->getParam('id');
            $type = $this->_request->getParam('type');
            $model = new Model_Wep();
            $model->delete($id, $type);
            //@todo delete all the activity and the elements
            $this->_helper->FlashMessenger->addMessage(array('message' => "Activities Saved."));

            $this->_redirect('wep/list-activities?account_id=' . $identity->account_id . '&type=iati_activities');
        }
    }

    public function deleteActivityAction()
    {
        try{
            $activityId = (isset($_GET['activity_id']))?$_GET['activity_id']:NULL;
            //$className = (isset($_GET['classname']))?$_GET['classname']:NULL;

            $activityModel = new Model_Activity();
            $activityModel->deleteActivityById($activityId);

            $this->_helper->FlashMessenger->addMessage(array('message' => "Activity Deleted."));
            $this->_redirect('wep/view-activities');
        }
        catch(Exception $e){

        }
    }


    public function formAction()
    {
        //        print "dfasf";exit();
        $identity = Zend_Auth::getInstance()->getIdentity();
        if ($_GET) {
            $name = $this->getRequest()->getParam('name');
            $string = 'Form_Wep_' . $name;
            $form = new $string();
            $form->add('', $identity->account_id);

            if ($_POST) {
                //                print_r($_POST);exit();
            }
            $this->view->form = $form;
        }

        $this->view->blockManager()->enable('partial/activitymenu.phtml');
    }


    function flatArray ($array) {
        $result = array();

        foreach ($array as $key => $val) {
            array_push($result, $this->recurArray($key, $val, array()));
        }

        //    print_r($result);

        $result_depths = array();
        foreach($result as $array) {
            $depth = (is_array($array)) ? $this->array_depth($array) : 1;
            array_push($result_depths, $depth);
        }

        $max_depth = max($result_depths);

        $final = $this->combineAll($result, $max_depth);

        //    print_r($final);exit;

        //print_r($final['0']);
        foreach($final as $key => $val) {
            if (!is_array($val)) {
                continue;
            }

            $result_depths = array();
            foreach($final[$key] as $array) {
                $depth = (is_array($array)) ? $this->array_depth($array) : 1;
                array_push($result_depths, $depth);
            }
            $max_depth = max($result_depths);
            $final[$key] = $this->combineAll($final[$key], $max_depth);

            foreach($final[$key] as $k => $v) {
                if (!is_array($v)) {
                    continue;
                }

                $result_depths = array();
                foreach($final[$key][$k] as $array) {
                    $depth = (is_array($array)) ? $this->array_depth($array) : 1;
                    array_push($result_depths, $depth);
                }
                $max_depth = max($result_depths);
                $final[$key][$k] = $this->combineAll($final[$key][$k], $max_depth);

            }

        }
        //    print_r($final);exit;

        return $final;
        //    print_r($final);
    }

    function combineAll($array, $max_depth=4, $depth=1, $result=array()) {
        $process = array();
        foreach($array as $k => $a) {
            if (is_array($a)) {
                if ($this->array_depth($a) == $depth) {
                    array_push($process, $a);
                }
            }
            else {
                $result[$k] = $a;
            }
        }

        if ($depth > $max_depth) {
            return $result;
        }

        while (!empty($process)) {
            $arr = array_shift($process);

            foreach ($arr as $key => $val) {
                if (isset($result[$key]) && is_array($result[$key])) {
                    //print_r($result[$key]);
                    /*
                    if (sizeof($val) < 2) {
                    list($k, $v) = each($val);
                    if (is_array($result[$key][$k])) {
                    array_push($result[$key][$k], $v);
                    }
                    else {
                    $result[$key][$k] = $v;
                    }
                    }
                    else {*/
                    array_push($result[$key], $val);
                    //}
                }
                else {
                    $result[$key] = $val;
                }
            }
        }

        return $this->combineAll($array, $max_depth, ++$depth, $result);
    }

    /**
     * Actual recursion happens here
     *
     */
    function recurArray ($key, $arr, $array) {

        if (is_array($arr)) {
            foreach ($arr as $k => $v) {
                $array[$k] = $this->recurArray($key, $v, array());
            }
        }
        else {
            return array($key => $arr);
        }

        return $array;
    }

    /**
     *
     * http://stackoverflow.com/questions/262891/
     *    is-there-a-way-to-find-how-how-deep-a-php-array-is
     */
    function array_depth ($array) {
        $max_depth = 1;

        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = $this->array_depth($value) + 1;

                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }
        return $max_depth;
    }

    public function updateStatusAction()
    {
        $ids = $this->getRequest()->getParam('ids');
        $state = $this->getRequest()->getParam('status');
        $activity_ids = explode(',',$ids);
        $db = new Model_ActivityStatus;
        $not_valid = false;
        /*
        if($ids)
        {
            foreach($activity_ids as $activity_id)
            {
                $activity_state = $db->getActivityStatus($activity_id);
                if(!Iati_WEP_ActivityState::isValidTransition($activity_state,$state)){
                    $not_valid = true;
                }
            }
        }
        */
        if($not_valid){
            $this->_helper->FlashMessenger->addMessage(array('warning' => "The activities cannot be changed to the state. Please check that a state to be changed is valid for all selected activities"));
        } else {
            if($state == Iati_WEP_ActivityState::STATUS_PUBLISHED){
                $identity = Zend_Auth::getInstance()->getIdentity();
                $account_id = $identity->account_id;

                $modelRegistryInfo = new Model_RegistryInfo();
                $registryInfo = $modelRegistryInfo->getOrgRegistryInfo($account_id);
                if(!$registryInfo){
                    $this->_helper->FlashMessenger->addMessage(array('error' => "Registry information not found. Please go to <a href='{$this->view->baseUrl()}/wep/edit-defaults'>Change Defaults</a> to add registry info."));
                } else if(!$registryInfo->publisher_id){
                    $this->_helper->FlashMessenger->addMessage(array('error' => "Publisher Id not found. Xml files could not be created. Please go to  <a href='{$this->view->baseUrl()}/wep/edit-defaults'>Change Defaults</a> to add publisher id."));
                } else {
                    $db->updateActivityStatus($activity_ids,(int)$state);
                    
                    $pub = new Iati_WEP_Publish($account_id, $registryInfo->publisher_id , $registryInfo->publishing_type,$activity_ids);
                    $pub->publish();

                    if($registryInfo->update_registry){
                        if(!$registryInfo->api_key){
                            $this->_helper->FlashMessenger->addMessage(array('error' => "Api Key not found. Activities could not be registered in IATI Registry. Please go to <a href='{$this->view->baseUrl()}/wep/edit-defaults'>Change Defaults</a> to add API key."));
                        } else {
                            $reg = new Iati_Registry($registryInfo->publisher_id , $registryInfo->api_key);
                            $modelPublished = new Model_Published();
                            $files = $modelPublished->getPublishedInfo($account_id);

                            foreach($files as $file){
                                $reg->prepareRegistryData($file);
                                $reg->publishToRegistry();
                            }

                            if($reg->getErrors()){
                                $this->_helper->FlashMessenger->addMessage(array('info' => 'Activities xml files created. '.$reg->getErrors()));
                            } else {
                                $this->_helper->FlashMessenger->addMessage(array('message' => "Activities published to IATI registry."));
                            }
                        }
                    } else {
                        $this->_helper->FlashMessenger->addMessage(array('message' => "Activities xml files created."));
                    }


                }
            } else {
                $db->updateActivityStatus($activity_ids,(int)$state);
            }
        }
        $this->_redirect('wep/view-activities');
    }

    public function publishInRegistryAction()
    {   
        $fileIds = explode(',' , $this->_getParam('file_ids'));
        
        if(!$fileIds[0]){
            $this->_helper->FlashMessenger->addMessage(array('info' => "Please select a file to register in IATI Registry."));
            $this->_redirect('wep/list-published-files');
        }
        $identity = Zend_Auth::getInstance()->getIdentity();
        $accountId = $identity->account_id;
        $modelRegistryInfo = new Model_RegistryInfo();
        $registryInfo = $modelRegistryInfo->getOrgRegistryInfo($accountId);

        if(!$registryInfo->api_key){
            $this->_helper->FlashMessenger->addMessage(array('error' => "Api Key not found. Activities could not be registered in IATI Registry. Please go to <a href='{$this->view->baseUrl()}/wep/edit-defaults'>Change Defaults</a> to add API key."));
        } else {
            $reg = new Iati_Registry($registryInfo->publisher_id , $registryInfo->api_key);
            $modelPublished = new Model_Published();
            $files = $modelPublished->getPublishedInfoByIds($fileIds);

            foreach($files as $file){
                $reg->prepareRegistryData($file);
                $reg->publishToRegistry();
            }

            if($reg->getErrors()){
                $this->_helper->FlashMessenger->addMessage(array('error' => $reg->getErrors()));
            } else {
                $this->_helper->FlashMessenger->addMessage(array('message' => "Activities registered to IATI registry."));
            }
        }

        $this->_redirect('wep/list-published-files');
    }

    public function updateActivityUpdatedDatetime($activity_id)
    {
        $model = new Model_Wep();
        $rowSet = $model->getRowsByFields('iati_activity', 'id', $activity_id);
        $data['id'] = $activity_id;
        $data['@last_updated_datetime'] = date('Y-m-d H:i:s');
        $result = $model->updateRowsToTable('iati_activity', $data);
    }

    public function getHelpMessageAction()
    {
        $element_name = $this->getRequest()->getParam('element');
        $model = new Model_Help();
        $message = $model->getHelpMessage($element_name);
        if(!$message['message'])
        {
            $message['message'] = 'No help is provided for this item';
        }
        echo $message['message'];exit;
        //$this->_helper->json($message['message']);
    }

    public function listPublishedFilesAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $orgId = $identity->account_id;
        $publishPermission = 1; // set publish permission to true so that we should only check permission for user.
        if($identity->role == 'user'){
            $model = new Model_Wep();
            $userPermission = $model->getUserPermission($identity->user_id);
            $publishPermission = $userPermission->hasPermission(Iati_WEP_PermissionConts::PUBLISH);
        }

        $modelRegistryInfo = new Model_RegistryInfo();
        $registryInfo = $modelRegistryInfo->getOrgRegistryInfo($orgId);
        
        // Create Registry Form For Activities
        $formForActivities = new Form_Wep_PublishToRegistry();
        $formForActivities->setAction($this->view->baseUrl().'/wep/publish-in-registry');
        if($registryInfo->update_registry){
            $formForActivities->push_to_registry->setAttrib('disabled', 'disabled');
        }
        $this->view->formForActivities = $formForActivities;
        
        // Create Registry Form For Organisation
        $formForOrganisation = new Form_Organisation_PublishToRegistry();
        $formForOrganisation->setAction($this->view->baseUrl().'/organisation/publish-in-registry');
        if($registryInfo->update_registry){
            $formForOrganisation->push_to_registry_for_organisation->setAttrib('disabled', 'disabled');
        }
        $this->view->formForOrganisation = $formForOrganisation;

        // Fetch Publish Data For Activities
        $db = new Model_Published();
        $publishedFilesOfActivities = $db->getAllPublishedInfo($orgId);
        $this->view->published_files_activities = $publishedFilesOfActivities;
        
        // Fetch Publish Data For Organisation
        $organisationpublishedModel = new Model_OrganisationPublished();
        $publishedFilesOfOrganisation = $organisationpublishedModel->getAllPublishedInfo($orgId);
        $this->view->published_files_organisation = $publishedFilesOfOrganisation;
        
        $this->view->publish_permission = $publishPermission;
    }

    public function deletePublishedFileAction()
    {
        $fileId = $this->_getParam('file_id');
        $db = new Model_Published();
        $publishedFiles = $db->deleteByFileId($fileId);

        $this->_helper->FlashMessenger->addMessage(array('message' => "File Deleted Sucessfully."));
        $this->_redirect('wep/list-published-files');
    }

    public function hasData($data)
    {
        if(isset($data['Activity_activity_id']))unset($data['Activity_activity_id']);
        if(isset($data['save']))unset($data['save']);

        foreach($data as $elements){
            if(!is_array($elements)){
                if($elements){
                    return true;
                }
            } else {
                return $this->hasData($elements);
            }
        }
        return false;
    }

    public function editElementAction()
    {
        $class = $this->_getParam('class');
        $activityId = $this->_getParam('activity_id');
        $model = new Model_Wep();

        $dbLayer = new Iati_WEP_DbLayer();
        $classname = 'Iati_WEP_Activity_'. $class . 'Factory';
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        $initial = $this->getInitialValues($activityId, $class);
        $factory = new $classname();
        $factory->setInitialValues($initial);
        $formHelper = new Iati_WEP_FormHelper();

        if($formData = $this->getRequest()->getPost()){
            $activity = new Iati_WEP_Activity_Elements_Activity();
            $activity->setAttributes(array('activity_id' => $activityId));
            $registryTree->addNode($activity);
            $tree = $factory->factory($class, $formData);
            $form = $formHelper->getForm();
            $form->populate($formData);
            if($form->validate()){
                $elementClassName = 'Iati_Activity_Element_Activity';
                $element = new $elementClassName ();
                $data = $activity->getCleanedData();
                $element->setAttribs($data);
                $activityTree = $factory->cleanData($activity, $element);
                $dbLayer->save($activityTree);
                $rowSet = $dbLayer->getRowSet($class, 'activity_id', $activityId, true);
                $factory->extractData($rowSet, $activityId);
                $form = $formHelper->getForm();

                //Update Activity Hash
                $activityHashModel = new Model_ActivityHash();
                $updated = $activityHashModel->updateHash($activityId);
                if(!$updated){
                    $type = 'info';
                    $message = 'No Changes Made';
                } else {
                    //update the activity so that the last updated time is updated
                    $this->updateActivityUpdatedDatetime($activityId);

                    //change state to editing
                    $db = new Model_ActivityStatus;
                    $db->updateActivityStatus($activityId,Iati_WEP_ActivityState::STATUS_EDITING);
                    $type = 'message';
                    $message = "$class successfully updated.";
                }
                $this->_helper->FlashMessenger->addMessage(array($type => $message));
                if(isset($formData['save_and_view'])){
                    $this->_redirect('wep/view-activity/'.$activityId);
                }
            } else {
                $form->populate($formData);
                $this->_helper->FlashMessenger->addMessage(array('error' => "You have some error in you data."));
            }
        } else {
            $rowSet = $dbLayer->getRowSet($class, 'activity_id', $activityId, true);
            $elements = $rowSet->getElements();
            $attributes = $elements[0]->getAttribs();
            if(empty($attributes)){
                $activity = new Iati_WEP_Activity_Elements_Activity();
                $activity->setAttributes(array('activity_id' => $activityId));
                $registryTree->addNode($activity);
                $factory->factory($class);
            } else {
                $factory->extractData($rowSet, $activityId);
            }
            $form = $formHelper->getForm();
        }
        $activity_info = $model->listAll('iati_activity', 'id', $activityId);
        if (empty($activity_info)) {
            //@todo
        }
        $activity = $activity_info[0];

        $iati_identifier_row = $model->getRowById('iati_identifier', 'activity_id', $activityId);
        $activity['iati_identifier'] = $iati_identifier_row['text'];
        $activity['activity_identifier'] = $iati_identifier_row['activity_identifier'];
        $title_row = $model->getRowById('iati_title', 'activity_id', $activityId);
        $activity['iati_title'] = $title_row['text'];

        $this->view->form = $form;
        $this->view->activityInfo = $activity;
        $this->view->blockManager()->enable('partial/override-activity.phtml');
        $this->view->blockManager()->enable('partial/activitymenu.phtml');
        $this->view->blockManager()->disable('partial/primarymenu.phtml');
        $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->disable('partial/usermgmtmenu.phtml');
        $this->view->blockManager()->disable('partial/published-list.phtml');
        $this->view->blockManager()->disable('partial/organisation-data.phtml'); 
    }
    
    public function updateReportingOrgAction()
    {
        $reportingOrgId = $this->_getParam('id');
        $activityId = $this->_getParam('activity_id');

        $model = new Model_ReportingOrg();
        $model->updateReportingOrg($reportingOrgId);
        
        $activityId = $model->getActivityIdById($reportingOrgId);

        //Update Activity Hash
        $activityHashModel = new Model_ActivityHash();
        $updated = $activityHashModel->updateHash($activityId);
        if(!$updated){
            $type = 'info';
            $message = "Already up to date. To make changes please change values in 'Change Defaults' and then update.";
        } else {
            //update the activity so that the last updated time is updated
            $this->updateActivityUpdatedDatetime($activityId);

            //change state to editing
            $db = new Model_ActivityStatus;
            $db->updateActivityStatus($activityId,Iati_WEP_ActivityState::STATUS_EDITING);
            $type = 'message';
            $message = "Updated Reporting Organisation sucessfully";
        }
        $this->_helper->FlashMessenger->addMessage(array($type => $message));
        $this->_redirect("/activity/edit-element/?activity_id=" . $activityId . "&className=Activity_ReportingOrg");
       
    }
}
