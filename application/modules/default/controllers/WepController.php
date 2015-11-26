<?php
/**
 * Handles default functionalities for AidStream.
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
        $this->view->blockManager()->enable('partial/download-my-data.phtml');
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
        if(!Simplified_Model_Simplified::isSimplified()){
            $this->view->blockManager()->enable('partial/uploaded-docs.phtml');
        }
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
        $this->view->registry_url = "/publisher/".$regInfo->publisher_id;
        $this->view->activities_id = $activities_id;

    }

    public function settingsAction()
    {
        if(Simplified_Model_Simplified::isSimplified()){
            $this->_redirect('simplified/default/settings');
        }
        $identity = Zend_Auth::getInstance()->getIdentity();
        $model = new Model_Wep();
        $modelRegistryInfo = new Model_RegistryInfo();

        $defaultFieldsValues = $model->getDefaults(
                                                   'default_field_values',
                                                   'account_id',
                                                   $identity->account_id
                                                );

        $default['field_values'] = $defaultFieldsValues->getDefaultFields();
        $defaultFieldGroup = $model->getDefaults(
                                                'default_field_groups',
                                                'account_id',
                                                $identity->account_id
                                            );
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
                    $this->_helper->FlashMessenger
                        ->addMessage(array('error' => "You have some error in your data"));
                    $form->populate($data);
                } else {
                    //Trim
                    $data['default_reporting_org'] = trim($data['default_reporting_org']); 
                    $data['reporting_org_ref'] = trim($data['reporting_org_ref']);

                    //Get default fields values for reporting org
                    $defaultFieldsValues = $model->getDefaults('default_field_values', 'account_id', $identity->account_id);
                    $defaults = $defaultFieldsValues->getDefaultFields();
                    $reportingOrgOld['@ref'] = $defaults['reporting_org_ref'];
                    $reportingOrgOld['@type'] = $defaults['reporting_org_type'];
                    $reportingOrgOld['@xml_lang'] = $defaults['reporting_org_lang'];
                    $reportingOrgOld['text'] = $defaults['reporting_org'];
                    $reportingOrgOld = serialize($reportingOrgOld);

                    //Get values from settings form
                    $reportingOrgNew['@ref'] = $data['reporting_org_ref'];
                    $reportingOrgNew['@type'] = $data['reporting_org_type'];
                    $reportingOrgNew['@xml_lang'] = $data['reporting_org_lang'];
                    $reportingOrgNew['text'] = $data['default_reporting_org'];
                    $reportingOrgNew = serialize($reportingOrgNew);

                    //Update Publishing Info
                    $modelRegistryInfo->updateRegistryInfoFromData($data);

                    //Update Default Values
                    $defaults = new Model_Defaults();
                    $defaults->updateDefaults($data);

                    if (isset($_GET['btn'])) {
                        $save = $_GET['btn'];
                    }

                    //If reporting org change
                    if ($reportingOrgNew != $reportingOrgOld) {
                        if ($save == "ok") {
                            $model->updateIatiIdentifiers($data['reporting_org_ref']);
                            $model->settingsChange();
                            //Check push_to_registry for activity
                            $modelPublished = new Model_Published();
                            $activityPublish = $modelPublished->isPushedToRegistry($identity->account_id);
                            if ($activityPublish) {
                                $this->pushToRegistry('Published');
                            }

                            //Check push_to_registry for organisation data
                            $modelOrganisationPublished = new Model_OrganisationPublished();
                            $organisationPublish = $modelOrganisationPublished->isPushedToRegistry($identity->account_id);
                            if ($organisationPublish) {
                                $this->pushToRegistry('OrganisationPublished');
                            }
                        }
                    }

                    $this->_helper->FlashMessenger
                        ->addMessage(array('message' => "Settings successfully updated."));
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

    /**
     * Push already pushed activities if reporting org has been changed in settings.
     */
    private function pushToRegistry($publish)
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $account_id = $identity->account_id;

        $modelRegistryInfo = new Model_RegistryInfo();
        $registryInfo = $modelRegistryInfo->getOrgRegistryInfo($account_id);
        if(!$registryInfo){
            $this->_helper->FlashMessenger
                ->addMessage(array('error' => "Registry information not found.
                                   Please go to
                                   <a href='{$this->view->baseUrl()}/wep/settings'>Settings</a>
                                   to add registry info."));
        } else if(!$registryInfo->publisher_id){
            $this->_helper->FlashMessenger
                ->addMessage(array('error' => "Publisher Id not found. IATI
                                   Activities files could not be created. Please go to
                                   <a href='{$this->view->baseUrl()}/wep/settings'>Settings</a>
                                   to add publisher id."));
        } else {
            if(!$registryInfo->api_key){
                $this->_helper->FlashMessenger
                    ->addMessage(array('error' => "Api Key not found.
                                       Activities could not be registered in
                                       IATI Registry. Please go to
                                       <a href='{$this->view->baseUrl()}/wep/settings'>Settings</a>
                                       to add API key."));
            } else {
                $model = 'Model_' . $publish;
                $modelPublished = new $model();
                $files = $modelPublished->getPushedToRegistryInfo($account_id);
                if ($publish == 'OrganisationPublished') {
                    $published =  Model_Registry::publish($files , $account_id , $registryInfo, true);
                } elseif ($publish == 'Published') {
                    $published =  Model_Registry::publish($files , $account_id , $registryInfo);
                }
                if($published['error']){
                    $this->_helper->FlashMessenger
                        ->addMessage(array('error' => $published['error']));
                } else {
                    $this->_helper->FlashMessenger
                        ->addMessage(array('message' => "Activities have been
                                           registered to IATI registry."));
                }
            }
        }

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

        $activity_info['@xml_lang'] = $wepModel->fetchValueById(
                                                            'Language' ,
                                                            $default['language'] ,
                                                            'Code');
        $activity_info['@default_currency'] = $wepModel->fetchValueById(
                                                            'Currency' ,
                                                            $default['currency'] ,
                                                            'Code');

        $activity_info['@hierarchy'] = $default['hierarchy'];
        $activity_info['@linked_data_uri'] = $default['linked_data_default'];
        $activity_info['@last_updated_datetime'] = date('Y-m-d H:i:s');
        $activity_info['activities_id'] = $activities_id;

        $reporting_org_info['@reporting_org_name'] = $default['reporting_org'];
        $reporting_org_info['@reporting_org_ref'] = $default['reporting_org_ref'];
        $reporting_org_info['@reporting_org_type'] = $wepModel->fetchValueById(
                                                                    'OrganisationType' ,
                                                                    $default['reporting_org_type'] ,
                                                                    'Name');
        $reporting_org_info['@reporting_org_lang'] = $wepModel->fetchValueById(
                                                                    'Language' ,
                                                                    $default['reporting_org_lang'] ,
                                                                    'Name');
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
                $this->_helper->FlashMessenger
                    ->addMessage(array(
                                    'message' => "Before you start entering
                                    activity data you need to add some default
                                    values that will automatically be filled in for
                                    each activity you report."
                                    )
                                );
                $this->_redirect('wep/settings');
            } else { // For other user redirect to dashboard.
                $this->_helper->FlashMessenger
                    ->addMessage(array(
                                    'message' => "All information for Reporting
                                     Organisation is not provided .Please contact
                                     your organisation admin"
                                    )
                                );
                $this->_redirect('wep/dashboard');
            }
        }

        $activityDefaults['@collaboration_type'] = $wepModel->fetchValueById(
                                                            'CollaborationType',
                                                            $default['collaboration_type'],
                                                            'Name');
        $activityDefaults['@flow_type'] = $wepModel->fetchValueById(
                                                        'FlowType' ,
                                                        $default['flow_type'],
                                                        'Name');
        $activityDefaults['@finance_type'] = $wepModel->fetchValueById(
                                                            'FinanceType' ,
                                                            $default['finance_type'],
                                                            'Name');
        $activityDefaults['@aid_type'] = $wepModel->fetchValueById(
                                                        'AidType' ,
                                                        $default['aid_type'],
                                                        'Name');
        $activityDefaults['@tied_status'] = $wepModel->fetchValueById(
                                                            'TiedStatus' ,
                                                            $default['tied_status'],
                                                            'Name');

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
                    $activity_id = $activityModel->createActivity(
                                                        $activities_id ,
                                                        $default ,
                                                        $iatiIdentifier
                                                    );

                    //Create Activity Hash
                    $activityHashModel = new Model_ActivityHash();
                    $updated = $activityHashModel->updateActivityHash($activity_id);

                    $this->_helper->FlashMessenger
                        ->addMessage(array('message' => "Congratulations! You have
                                           successfully created an activity."));
                    $this->_redirect('activity/view-activity-info/?activity_id=' . $activity_id);
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
        $defaultFieldValues = $model->getDefaults(
                                                'default_field_values',
                                                'account_id',
                                                $identity->account_id
                                            );
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
        $globalobj->propertySetter(array('activity_id' => $activity_id));
        return $globalobj;
    }

    public function addToRegistry($object, $parent = NULL)
    {
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        $registryTree->addNode($object, $parent);
        return $registryTree;
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
                $narrative_title = '';
                $title = $wepModel->getRowById('iati_title', 'activity_id', $activity['id']);
                if ($title['id'])
                    $narrative_title = $wepModel->listAll('iati_title/narrative', 'title_id', $title['id']);
                
                $identifier = $wepModel->listAll('iati_identifier', 'activity_id', $activity['id']);
                if (is_array($narrative_title)) { 
                    $activity_array[$i]['title'] = ($narrative_title[0]['text'])?$narrative_title[0]['text']:'No title';
                } else {
                    $activity_array[$i]['title'] = 'No title';
                }
                $activity_array[$i]['identifier'] = ($identifier[0]['activity_identifier'])?
                    $identifier[0]['activity_identifier']:'No Activity Identifier';
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



    public function editActivityAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        if ($_GET) {
            $wepModel = new Model_Wep();
            if(isset($_GET['activities_id'])){
                $exists = $wepModel->getRowById('iati_activities', 'id', $_GET['activities_id']);
                if(!$exists){
                    $this->_helper->FlashMessenger
                        ->addMessage(array('message' => "Activities does not exist."));

                    $this->_redirect('/user/user/login');
                }

                $activities_id = $this->getRequest()->getParam('activities_id');
                $rowSet = $wepModel->getRowsByFields(
                                                    'default_field_values',
                                                    'account_id',
                                                    $identity->account_id
                                                );
                $defaultValues = unserialize($rowSet[0]['object']);
                $default = $defaultValues->getDefaultFields();
                $activity['xml_lang'] = $default['language'];
                $activity['default_currency'] = $default['currency'];
                $activity['hierarchy'] = $default['hierarchy'];
                $activity['linked_data_uri'] = $default['linked_data_default'];
                $form = new Form_Wep_IatiActivity();
                $form->add('add', $identity->account_id);
                $form->populate(array('reporting_org'=>$default['reporting_org_ref']));

            }
            if(isset($_GET['activity_id'])){
                $exists = $wepModel->getRowById('iati_activity', 'id', $_GET['activity_id']);
                if(!$exists){
                    $this->_helper->FlashMessenger
                        ->addMessage(array('warning' => "Activity does not exist."));

                    $this->_redirect('/user/user/login');
                }
                $activity_id = $this->getRequest()->getParam('activity_id');
                $rowSet = $wepModel->getRowsByFields('iati_activity', 'id', $activity_id);
                $activity['xml_lang'] = $rowSet[0]['@xml_lang'];
                $activity['default_currency'] = $rowSet[0]['@default_currency'];
                $activity['hierarchy'] = $rowSet[0]['@hierarchy'];
                $activity['activities_id'] = $rowSet[0]['activities_id'];
                $activity['linked_data_uri'] = $rowSet[0]['@linked_data_uri'];
                $form = new Form_Wep_EditIatiActivity();
                $form->edit($identity->account_id);


                $aActivityInfo = $wepModel->listAll('iati_activity', 'id', $activity_id);
                $activityInfo = $aActivityInfo[0];
                $iati_identifier_row = $wepModel->getRowById(
                                                            'iati_identifier',
                                                            'activity_id',
                                                            $activity_id
                                                        );
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
                        $default['linked_data_default'] = $formData['linked_data_uri'];

                        $iatiIdentifier = array();
                        $iatiIdentifier['iati_identifier'] = $formData['iati_identifier_text'];
                        $iatiIdentifier['activity_identifier'] = $formData['activity_identifier'];

                        $activityModel = new Model_Activity();
                        $activityId = $activityModel->createActivity(
                                                                    $activities_id ,
                                                                    $default ,
                                                                    $iatiIdentifier
                                                                );

                        //Create Activity Hash
                        $activityHashModel = new Model_ActivityHash();
                        $updated = $activityHashModel->updateActivityHash($activityId);

                        $this->_helper->FlashMessenger
                            ->addMessage(array('message' => "Activity Sucessfully Created."));
                        $this->_redirect('activity/view-activity-info/?activity_id=' . $activityId);
                    }

                    if(isset($_GET['activity_id'])){
                        //$data['activities_id'] = $rowSet[0]['activities_id'];
                        $data['id'] = $activity_id;
                        $data['@xml_lang'] = $formData['xml_lang'];
                        $data['@default_currency'] = $formData['default_currency'];
                        $data['@hierarchy'] = $formData['hierarchy'];
                        $data['@linked_data_uri'] = $formData['linked_data_uri'];
                        $result = $wepModel->updateRowsToTable('iati_activity', $data);
                        $wepModel = new Model_Wep();
                        $activityHashModel = new Model_ActivityHash();
                        $updated = $activityHashModel->updateActivityHash($activity_id);
                        if(!$updated){
                            $type = 'info';
                            $message = 'No Changes Made';
                        } else {
                            //change state to editing
                            $db = new Model_ActivityStatus;
                            $db->updateActivityStatus(
                                                      $activity_id,
                                                      Iati_WEP_ActivityState::STATUS_DRAFT
                                                    );
                            $type = 'message';
                            $message = "Activity overridden";
                        }
                        $this->_helper->FlashMessenger
                                ->addMessage(array($type => $message));
                    }
                    $this->_redirect('activity/view-activity-info/?activity_id=' . $activity_id);
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

    public function updateStatusAction()
    {
        $ids = $this->getRequest()->getParam('ids');
        $state = $this->getRequest()->getParam('status');
        $redirect = $this->getRequest()->getParam('redirect');
        $activity_ids = explode(',',$ids);
        $db = new Model_ActivityStatus;

        if($state == Iati_WEP_ActivityState::STATUS_PUBLISHED){
            $identity = Zend_Auth::getInstance()->getIdentity();
            $account_id = $identity->account_id;

            $modelRegistryInfo = new Model_RegistryInfo();
            $registryInfo = $modelRegistryInfo->getOrgRegistryInfo($account_id);
            if(!$registryInfo){
                $this->_helper->FlashMessenger
                    ->addMessage(array('error' => "Registry information not found.
                                       Please go to
                                       <a href='{$this->view->baseUrl()}/wep/settings'>Settings</a>
                                       to add registry info."));
            } else if(!$registryInfo->publisher_id){
                $this->_helper->FlashMessenger
                    ->addMessage(array('error' => "Publisher Id not found. IATI
                                       Activities files could not be created. Please go to
                                       <a href='{$this->view->baseUrl()}/wep/settings'>Settings</a>
                                       to add publisher id."));
            } else {
                $db->updateActivityStatus($activity_ids,(int)$state);

                $pub = new Iati_WEP_Publish(
                                            $account_id,
                                            $registryInfo->publisher_id ,
                                            $registryInfo->publishing_type);
                $pub->publish();

                if($registryInfo->update_registry){
                    if(!$registryInfo->api_key){
                        $this->_helper->FlashMessenger
                            ->addMessage(array('error' => "Api Key not found.
                                               Activities could not be registered in
                                               IATI Registry. Please go to
                                               <a href='{$this->view->baseUrl()}/wep/settings'>Settings</a>
                                               to add API key."));
                    } else {
                        $modelPublished = new Model_Published();
                        $files = $modelPublished->getPublishedInfo($account_id);

                        $published =  Model_Registry::publish($files , $account_id , $registryInfo);

                        if($published['error']){
                            $this->_helper->FlashMessenger
                                ->addMessage(array('error' => $published['error']));
                        } else {
                            $this->_helper->FlashMessenger
                                ->addMessage(array('message' => "Activities have been
                                                   registered to IATI registry."));
                        }
                    }
                } else {
                    $this->_helper->FlashMessenger
                        ->addMessage(array('message' => "IATI Activities files
                                           have been created. <a href='".$this->view->baseUrl()
                                           ."/wep/list-published-files'>View your
                                           published files</a>"));
                }
            }
        } else {
            if ($state == Iati_WEP_ActivityState::STATUS_COMPLETED){
                $errors = Iati_WEP_ElementValueCheck::checkDefaults($activity_ids);
                if ($errors) {
                    $errors = implode("<br> - ", (reset($errors)));
                    $this->_helper->FlashMessenger
                        ->addMessage(array(
                                'error' => 'Please make sure that you have following elements before changing to completed state: <br> - ' . $errors  . '<br>IATI Standard v2.01 
                                            <a href="http://support.iatistandard.org/entries/52502089-Upgrading-to-2-01-What-s-involved-A-non-technical-summary">requires</a> 
                                            that you have those minimum elements.'
                            ));
                } else {
                    $db->updateActivityStatus($activity_ids,(int)$state);
                }
            } else {
                $db->updateActivityStatus($activity_ids,(int)$state);
            }
        }
        if($redirect){
            $this->_redirect($redirect , array('prependBase'=>false));
        }
        $this->_redirect('wep/view-activities');
    }

    public function publishInRegistryAction()
    {
        $fileIds = explode(',' , $this->_getParam('file_ids'));
        if(!($fileIds[0])){
            $this->_helper->FlashMessenger
                ->addMessage(array('info' => "Please select a file to register
                                   in IATI Registry."));
            $this->_redirect('wep/list-published-files');
        }
        $identity = Zend_Auth::getInstance()->getIdentity();
        $accountId = $identity->account_id;

        $modelRegistryInfo = new Model_RegistryInfo();
        $registryInfo = $modelRegistryInfo->getOrgRegistryInfo($accountId);

        if(!$registryInfo->api_key){
            $this->_helper->FlashMessenger
                ->addMessage(array('error' => "Api Key not found. Activities
                                   could not be registered in IATI Registry. Please go to
                                   <a href='{$this->view->baseUrl()}/wep/settings'>Settings</a>
                                   to add API key."));
        } else {
            $modelPublished = new Model_Published();
            $files = $modelPublished->getPublishedInfoByIds($fileIds);

            $published =  Model_Registry::publish($files , $accountId , $registryInfo);
            if($published['error']){
                $this->_helper->FlashMessenger
                    ->addMessage(array('error' => $published['error']));
            } else {
                $this->_helper->FlashMessenger
                    ->addMessage(array('message' => "Activities registered to
                                       IATI registry."));
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
        // set publish permission to true so that we should only check permission for user.
        $publishPermission = 1;
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
        $formForOrganisation->setAction($this->view->baseUrl()
                                            .'/organisation/publish-in-registry');
        if($registryInfo->update_registry){
            $formForOrganisation->push_to_registry_for_organisation
                ->setAttrib('disabled', 'disabled');
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

        if(Simplified_Model_Simplified::isSimplified()){
            $this->view->blockManager()->disable('partial/organisation-data.phtml');
            $this->view->blockManager()->enable('partial/simplified-info.phtml');
        }
    }

    public function deletePublishedFileAction()
    {
        $fileId = $this->_getParam('file_id');
        $db = new Model_Published();
        $publishedFiles = $db->deleteByFileId($fileId);

        $this->_helper->FlashMessenger
            ->addMessage(array('message' => "File Deleted Sucessfully."));
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

    public function updateReportingOrgAction()
    {
        $reportingOrgId = $this->_getParam('id');
        $activityId = $this->_getParam('activity_id');

        $model = new Model_ReportingOrg();
        $model->updateReportingOrg($reportingOrgId);

        $activityId = $model->getActivityIdById($reportingOrgId);

        //Update Activity Hash
        $activityHashModel = new Model_ActivityHash();
        $updated = $activityHashModel->updateActivityHash($activityId);
        if(!$updated){
            $type = 'message';
            $message = "Already up to date. To make changes please change
                values in 'Settings' and then update.";
        } else {
            //update the activity so that the last updated time is updated
            $this->updateActivityUpdatedDatetime($activityId);

            //change state to editing
            $db = new Model_ActivityStatus();
            $db->updateActivityStatus($activityId,Iati_WEP_ActivityState::STATUS_DRAFT);
            $type = 'message';
            $message = "Updated Reporting Organisation sucessfully";
        }
        $this->_helper->FlashMessenger->addMessage(array($type => $message));
        $this->_redirect("/activity/edit-element/?activity_id=" . $activityId
                         . "&className=Activity_ReportingOrg");
    }

    public function downloadMyDataAction(){}

    public function downloadCsvAction()
    {
        $type = $this->_getParam('type');
        $obj = new Model_CsvDownload();
        echo $obj->downloadCsv($type);
        exit;
    }

    public function uploadTransactionAction()
    {
        $activityId = $this->_getParam('activity_id');

        $form = new Form_Wep_UploadTransaction();

        if($data = $this->getRequest()->getPost()){
            if($form->isValid($data)){
                $uploadDir = Zend_Registry::get('config')->public_folder."/files/csv/uploads";
                $upload = new Zend_File_Transfer_Adapter_Http();
                $upload->setDestination($uploadDir);
                $source = $upload->getFileName();
                try{
                        $upload->receive();
                        $csvHandler = new Model_CsvUpload();
                        $csvHandler->setInputFile($source);
                        $csvHandler->readCsv();
                        $headerCount = $csvHandler->countHeader();
                        if ($headerCount == 25) {
                            $count = $csvHandler->uploadDetailDataToTransaction($activityId);
                        } elseif ($headerCount == 14) {
                            $count = $csvHandler->uploadSimpleDataToTransaction($activityId);
                        }
                        if(!$count){
                            $messanger = $this->_helper->FlashMessenger;
                            $messanger->addMessage(array('trans-error' => 'You have
                                                         following errors. Please correct the errors.'));
                            if ($csvHandler->getErrors()) {
                                foreach($csvHandler->getErrors() as $tranCount => $error){
                                    $tranNo = $tranCount+1;
                                    if(!empty($error)){
                                        $messanger->addMessage(
                                                array('trans-error' =>"<div class='tran-no'>
                                                      Transaction no: {$tranNo}</div>"));
                                        foreach($error as $errormessage){
                                            $messanger->addMessage(
                                                array('trans-error' => " # ".$errormessage['message']));
                                        }
                                    }
                                }
                            } else {
                                $messanger->addMessage(array('trans-error' => "<div class='tran-no'>
                                                    Something went wrong. Please check your csv file.</div>"));
                            }
                        } else {
                            $activityHashModel = new Model_ActivityHash();
                            $updated = $activityHashModel->updateActivityHash($activityId);
                            if(!$updated){ // Update hash and status
                                $type = 'message';
                                $message = 'No Changes Made';
                            } else {
                                $oldState = Model_Activity::getActivityStatus($activityId);
                                Model_Activity::updateActivityUpdatedInfo($activityId);
                            }

                            if($updated && $oldState != Iati_WEP_ActivityState::STATUS_DRAFT){
                                // In case of update notify the user about state change.
                                $this->_helper->FlashMessenger
                                    ->addMessage(array('state-change-flash-message' => "The
                                                    activity state is changed back to Draft.
                                                    You must complete and verify in order
                                                    to publish the activity."));
                            }

                            $this->_helper->FlashMessenger
                                ->addMessage(array('message' => "{$count['add']} transaction(s) added. {$count['update']} transaction(s) updated."));
                            $this->_redirect("/activity/list-elements/?activity_id=".$activityId
                                             ."&classname=Activity_Transaction");
                        }

                } catch(Zend_File_Transfer_Exception $e) {
                    $e->getMessage();
                }
            } else {
                $form->populate($data);
            }
        }
        $this->view->form = $form;
        $this->view->activityId = $activityId;
        $this->view->blockManager()->enable('partial/override-activity.phtml');
        $this->view->blockManager()->enable('partial/activitymenu.phtml');
        $this->view->blockManager()->disable('partial/primarymenu.phtml');
        $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->disable('partial/usermgmtmenu.phtml');
        $this->view->blockManager()->disable('partial/published-list.phtml');
        $this->view->blockManager()->disable('partial/organisation-data.phtml');
        $this->view->blockManager()->disable('partial/uploaded-docs.phtml');
        $this->view->blockManager()->disable('partial/download-my-data.phtml');

    }

    public function listUploadedDocumentsAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $accountId = $identity->account_id;

        $model = new Model_UserDocument();

        $docs = $model->fetchAllDocuments($accountId);
        $usedDocs = $model->fetchUsedDocuments($accountId);

        $this->view->docs = $docs;
        $this->view->usedDocs = $usedDocs;
    }

    public function downloadXmlAction()
    {
        $name = $this->_getParam('name');
        $identity = Zend_Auth::getInstance()->getIdentity();
        
        $obj = new Iati_Core_Xml();

        if ($name == 'Activity') {
            $wepModel = new Model_Wep();
            $activities = $wepModel->listAll('iati_activities', 'account_id', $identity->account_id);
            $activities_id = $activities[0]['id'];
            $activityArray = $wepModel->listAll('iati_activity', 'activities_id', $activities_id);
            $index = 0;
            foreach ($activityArray as $key => $activity) {
                $activity_array[$index] = $activity['id'];
                $index++;
            }
            $xmlOutput = $obj->generateXml($name, $activity_array);
            $filename = 'iati_activity';
        } elseif ($name == 'Organisation') {
            $organisationModelObj = new Model_Organisation();
            $organisationId = $organisationModelObj->checkOrganisationPresent($identity->account_id);
            $organisationIds = explode(',', $organisationId);
            $xmlOutput = $obj->generateXml($name, $organisationIds);
            $filename = 'iati_organisation_data';
        } else {
            exit('Invalid name for download.');
        }
        ob_end_clean(); 
        header( 'Content-Type: text/xml' );
        header( 'Content-Disposition: attachment;filename='.$filename.'.xml');
        echo $xmlOutput;
        exit;
    }

}