<?php

/**
 * Controller to handle actions to organisation data. It handles all CRUD and other operations for organisation.
 * @author YIPL Dev team
 */
class OrganisationController extends Zend_Controller_Action
{

    public function init()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $this->_helper->layout()->setLayout('layout_wep');
        $this->view->blockManager()->enable('partial/dashboard.phtml');
        $this->view->blockManager()->enable('partial/primarymenu.phtml');
        $this->view->blockManager()->enable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->enable('partial/published-list.phtml');
        $this->view->blockManager()->enable('partial/organisation-data.phtml');
        $this->view->blockManager()->enable('partial/organisation-menu.phtml');

        // for role user check if the user has permission to add, publish ,if not disable menu.
        if ($identity->role == 'user')
        {
            $model = new Model_Wep();
            $userPermission = $model->getUserPermission($identity->user_id);
            $permission = $userPermission->hasPermission(Iati_WEP_PermissionConts::ADD_ACTIVITY);
            $publishPermission = $userPermission->hasPermission(Iati_WEP_PermissionConts::PUBLISH);
            if (!$permission)
            {
                $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
            }
            if (!$publishPermission)
            {
                $this->view->blockManager()->disable('partial/published-list.phtml');
            }
        }
        $this->view->blockManager()->enable('partial/usermgmtmenu.phtml');

    }
    
    /**
     * Add Element Of An Organisation  
     */
    public function addElementsAction()
    {
        $elementClass = $this->_getParam('className');
        $parentId = $this->_getParam('parentId');

        if (!$elementClass)
        {
            $this->_helper->FlashMessenger->addMessage(array('error' => "Could not fetch element."));
            $this->_redirect("/wep/dashboard");
        }

        $elementName = "Iati_Aidstream_Element_" . $elementClass;
        $element = new $elementName();

        if ($data = $this->getRequest()->getPost())
        {   
            $hasData = $element->hasData($data[$element->getClassName()]);
            $element->setData($data[$element->getClassName()]);
            $form = $element->getForm();
            if ($form->validate())
            {   
                if(!$hasData)
                {
                    $this->_helper->FlashMessenger->addMessage(array('message' => "You have not entered any data."));
                    $this->_redirect("/organisation/add-elements/?className=$elementClass&parentId=$parentId");
                }
                $id = $element->save($data[$element->getClassName()] , $parentId);

                //Update organisation hash
                $organisationHashModel = new Model_OrganisationHash();
                $updated = $organisationHashModel->updateHash($parentId);
                if (!$updated)
                {
                    $type = 'message';
                    $message = 'No Changes Made';
                } else
                {
                    //update the organisation so that the last updated time is updated
                    $wepModel = new Model_Wep();
                    $organisationData = array();
                    $organisationData['@last_updated_datetime'] = date('Y-m-d h:i:s');
                    $organisationData['id'] = $parentId;
                    $wepModel->updateRowsToTable('iati_organisation' , $organisationData);

                    //change state to editing
                    $db = new Model_OrganisationState;
                    $db->updateOrganisationState($parentId , Iati_WEP_ActivityState::STATUS_EDITING);
                    $type = 'message';
                    $message = $element->getDisplayName() . " successfully inserted.";
                }
                $this->_helper->FlashMessenger->addMessage(array($type => $message));
                if ($parentId)
                {
                    $idParam = "parentId={$parentId}";
                } else
                {
                    $idParam = "id={$id}";
                }
                if ($_POST['save_and_view'])
                {
                    $this->_redirect("organisation/view-elements/?parentId=$parentId");
                }
                $this->_redirect("/organisation/edit-elements/?className={$elementClass}&${idParam}");
            } else
            {
                $form->populate($data);
                $this->_helper->FlashMessenger->addMessage(array('error' => "You have some problem in your data. Please correct and save again"));
            }
        } else
        {
            $form = $element->getForm();
        }
        $form->addSubmitButton('Save');
        $this->view->form = $form;

        // Fetch Title
        $wepModel = new Model_Wep();
        $reportingOrg = $wepModel->getRowsByFields('iati_organisation/reporting_org' , 'organisation_id' , $parentId);
        $title = $reportingOrg[0]['text'];
        $this->view->title = $title . " Organisation File";

        //Set organisation id to view 
        $this->view->parentId = $parentId;

        $this->view->blockManager()->enable('partial/organisation-menu.phtml');
        $this->view->blockManager()->disable('partial/primarymenu.phtml');
        $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->disable('partial/usermgmtmenu.phtml');
        $this->view->blockManager()->disable('partial/published-list.phtml');
        $this->view->blockManager()->disable('partial/organisation-data.phtml');

    }
    
    /**
     * Edit Element Of An Organisation 
     */
    public function editElementsAction()
    {
        $elementClass = $this->_getParam('className');
        $eleId = $this->_getParam('id');
        $parentId = $this->_getParam('parentId');

        if (!$elementClass)
        {
            $this->_helper->FlashMessenger->addMessage(array('error' => "Could not fetch element."));
            $this->_redirect("/wep/dashboard");
        }

        if (!$eleId && !$parentId)
        {
            $this->_helper->FlashMessenger->addMessage(array('error' => "No id provided."));
            $this->_redirect("/wep/dashboard");
        }

        $elementName = "Iati_Aidstream_Element_" . $elementClass;
        $element = new $elementName();         

        if ($data = $this->getRequest()->getPost())
        {   
            $hasData = $element->hasData($data[$element->getClassName()]); 
            $element->setData($data[$element->getClassName()]);
            $form = $element->getForm();
            if ($form->validate())
            {  
                if(!$hasData)
                {
                    $this->_helper->FlashMessenger->addMessage(array('message' => "You have not entered any data."));
                    $this->_redirect("/organisation/add-elements/?className=$elementClass&parentId=$parentId");
                }
                $element->save($data[$element->getClassName()] , $parentId);

                //Update organisation hash
                $organisationHashModel = new Model_OrganisationHash();
                $updated = $organisationHashModel->updateHash($parentId);
                if (!$updated)
                {
                    $type = 'message';
                    $message = 'No Changes Made';
                } else
                {
                    //update the organisation so that the last updated time is updated
                    $wepModel = new Model_Wep();
                    $organisationData = array();
                    $organisationData['@last_updated_datetime'] = date('Y-m-d h:i:s');
                    $organisationData['id'] = $parentId;
                    $wepModel->updateRowsToTable('iati_organisation' , $organisationData);

                    //change state to editing
                    $db = new Model_OrganisationState;
                    $db->updateOrganisationState($parentId , Iati_WEP_ActivityState::STATUS_EDITING);
                    $type = 'message';
                    $message = $element->getDisplayName() . " successfully updated.";
                }
                $this->_helper->FlashMessenger->addMessage(array($type => $message));

                if ($_POST['save_and_view'])
                {
                    $this->_redirect('organisation/view-elements/?parentId=' . $parentId);
                }
            } else
            {
                $form->populate($data);
                $this->_helper->FlashMessenger->addMessage(array('error' => "You have some problem in your data. Please correct and save again"));
            }
        } else
        {
            if ($parentId)
            {   // Parent id is a id of an organisation
                if($element->getDisplayName() == 'Organisation Default')
                { 
                    $data = $element->fetchData($parentId);   
                } else
                {    
                    $data[$element->getClassName()] = $element->fetchData($parentId , true);
                }
            } else
            {
                if($eleId)
                {   
                     $data = $element->fetchData($eleId);  
                }
                else
                {  
                    $data[$element->getClassName()] = $element->fetchData($activityId,true);                    
                }    
            }
            if (empty($data[$element->getClassName()]))
            {
                $this->_redirect("/organisation/add-elements/?className=$elementClass&parentId=$parentId");
            } 
            $element->setData($data[$element->getClassName()]);
            $form = $element->getForm();
        }
        $form->addSubmitButton('Save');

        $this->view->form = $form;
        
        // Fetch title
        $wepModel = new Model_Wep();   
        $reportingOrg = $wepModel->getRowsByFields('iati_organisation/reporting_org' , 'organisation_id' , $parentId);
        $title = $reportingOrg[0]['text'];
        $this->view->title = $title . " Organisation File";
        
        // Set display name
        $this->view->displayName = $element->getDisplayName(); 
        //Set organisation id to view 
        $this->view->parentId = $parentId;          

        $this->view->blockManager()->enable('partial/organisation-menu.phtml');
        $this->view->blockManager()->disable('partial/primarymenu.phtml');
        $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->disable('partial/usermgmtmenu.phtml');
        $this->view->blockManager()->disable('partial/published-list.phtml');
        $this->view->blockManager()->disable('partial/organisation-data.phtml');

    }

    public function deleteOrganisationAction()
    {
        $elementClass = $this->_getParam('classname');
        $eleId = $this->_getParam('id');
        if (!$elementClass)
        {
            $this->_helper->FlashMessenger->addMessage(array('error' => "Could not fetch element."));
            $this->_redirect("/wep/dashboard");
        }

        if (!$eleId)
        {
            $this->_helper->FlashMessenger->addMessage(array('error' => "No id provided."));
            $this->_redirect("/wep/dashboard");
        }

        $elementName = "Iati_Aidstream_Element_" . $elementClass;
        $element = new $elementName();
        $element->deleteElement($eleId);

        $this->_helper->FlashMessenger->addMessage(array('message' => "Element Deleted sucessfully."));
        $this->_redirect("/wep/dashboard");

    }
    
    /**
     * Check Presence of an organisation for a login user 
     * If present, redirect to view-elements of an organisation
     * Else, redirect to add new organisation
     */
    public function organisationDataAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        
        // Check organisation is present or not for a login user
        $organisationModelObj = new Model_Organisation();
        $organisationId = $organisationModelObj->checkOrganisationPresent($identity->account_id);
        
        // Fetch default value for an organisation
        $model = new Model_Viewcode();
        $rowSet = $model->getRowsByFields('default_field_values' , 'account_id' , $identity->account_id);
        
        $defaultValues = unserialize($rowSet[0]['object']);
        $default = $defaultValues->getDefaultFields();
        
        // Check default value exist or not
        $wepModel = new Model_Wep();
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
                                                                 'message' => "Before you start entering organisation data
                                                                 you need to add some default values that will
                                                                 automatically be filled in for
                                                                 each organisation you report."
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
        
        if (!$organisationId)
        {
            $this->_redirect("organisation/add-organisation");
        }
        $this->_redirect("organisation/view-elements/?parentId=$organisationId");

    }
    
    /**
     * Add New organisation
     * Saved Default value 
     */
    public function addOrganisationAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        
        // Fetch default value for an organisation
        $model = new Model_Viewcode();
        $rowSet = $model->getRowsByFields('default_field_values' , 'account_id' , $identity->account_id);
        $defaultValues = unserialize($rowSet[0]['object']);
        $default = $defaultValues->getDefaultFields();
        
        // Saved default value for an organisation
        $organisationModel = new Model_Organisation();
        $organisationId = $organisationModel->createOrganisation($identity->account_id , $default);

        //Create Activity Hash
        $organisationHashModel = new Model_OrganisationHash();
        $updated = $organisationHashModel->updateHash($organisationId);

        $this->_redirect('organisation/view-elements/?parentId=' . $organisationId);

    }
    
    /**
     * Fetch Elements of an organisation 
     */
    public function viewElementsAction()
    {
        $organisationId = $this->getRequest()->getParam('parentId');

        // Fetch organisation data
        $organisationClassObj = new Iati_Aidstream_Element_Organisation();
        $organisations = $organisationClassObj->fetchData($organisationId , false);
        $this->view->organisations = $organisations;
        $this->view->parentId = $organisationId;

        // Fetch title
        $reportingOrgObj = new Iati_Aidstream_Element_Organisation_ReportingOrg();
        $reportingOrgs = $reportingOrgObj->fetchData($organisationId,true);
        $title = $reportingOrgs['text'];
        $this->view->title = $title . " Organisation File";

        // Get form for status change
        $state = $organisations['Organisation']['state_id'];
        $next_state = Iati_WEP_ActivityState::getNextStatus($state);
        if ($next_state && Iati_WEP_ActivityState::hasPermissionForState($next_state))
        {
            $status_form = new Form_Wep_ActivityChangeState();
            $status_form->setAction($this->view->baseUrl() . "/organisation/update-state");
            $status_form->ids->setValue($organisationId);
            $status_form->status->setValue($next_state);
            $status_form->change_state->setLabel(Iati_WEP_ActivityState::getStatus($next_state));
        } else
        {
            $status_form = null;
        }
        $this->view->status_form = $status_form;
        $this->view->state = $state;

        $this->view->blockManager()->enable('partial/organisation-menu.phtml');
        $this->view->blockManager()->disable('partial/primarymenu.phtml');
        $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->disable('partial/usermgmtmenu.phtml');
        $this->view->blockManager()->disable('partial/published-list.phtml');
        $this->view->blockManager()->disable('partial/organisation-data.phtml');

    }
    
    /**
     * Update State Of An Organisation 
     */
    public function updateStateAction()
    {
        $ids = $this->getRequest()->getParam('ids');
        $state = $this->getRequest()->getParam('status');
        
        $organisationIds = explode(',' , $ids);
        $db = new Model_OrganisationState;
        $not_valid = false;
        if ($not_valid)
        {
            $this->_helper->FlashMessenger->addMessage(array('warning' => "The organisation cannot be changed to the state. Please check that a state to be changed is valid for all selected organisations"));
        } else
        {
            if ($state == Iati_WEP_ActivityState::STATUS_PUBLISHED)
            {
                $identity = Zend_Auth::getInstance()->getIdentity();
                $account_id = $identity->account_id;

                $modelRegistryInfo = new Model_RegistryInfo();
                $registryInfo = $modelRegistryInfo->getOrgRegistryInfo($account_id);
                if (!$registryInfo)
                {
                    $this->_helper->FlashMessenger->addMessage(array('error' => "Registry information not found. Please go to <a href='{$this->view->baseUrl()}/wep/edit-defaults'>Change Defaults</a> to add registry info."));
                } else if (!$registryInfo->publisher_id)
                {
                    $this->_helper->FlashMessenger->addMessage(array('error' => "Publisher Id not found. Xml files could not be created. Please go to  <a href='{$this->view->baseUrl()}/wep/edit-defaults'>Change Defaults</a> to add publisher id."));
                } else
                {
                    $db->updateOrganisationState($organisationIds , (int) $state);

                    // Generate Xml
                    $obj = new Iati_Core_Xml();
                    $fileName = $obj->generateFile('organisation' , $organisationIds, $registryInfo->publisher_id);
                    
                    // Fetch last updated data's datetime
                    $wepModel = new Model_Wep();
                    $organsationInfo = $wepModel->getRowsByFields('iati_organisation' , 'id' , $organisationIds[0]);
                    $lastUpdateDatetime = $organsationInfo[0]['@last_updated_datetime'];
                    
                    //Set all status to 0
                    $modelPublished = new Model_OrganisationPublished();
                    $modelPublished->resetPublishedInfo($account_id);
                   
                    $organisationpublishedModel = new Model_OrganisationPublished();
                    $publishedData['publishing_org_id'] = $account_id;
                    $publishedData['filename'] = $fileName;
                    $publishedData['organisation_count'] = count($organisationIds);
                    $publishedData['data_updated_datetime'] = $lastUpdateDatetime;
                    $publishedData['published_date'] = date('Y-m-d H:i:s');
                    $publishedData['status'] = 1;
                    $organisationpublishedModel->savePublishedInfo($publishedData);

                    if ($registryInfo->update_registry)
                    {
                        if (!$registryInfo->api_key)
                        {
                            $this->_helper->FlashMessenger->addMessage(array('error' => "Api Key not found. Activities could not be registered in IATI Registry. Please go to <a href='{$this->view->baseUrl()}/wep/edit-defaults'>Change Defaults</a> to add API key."));
                        } else
                        {
                            $reg = new Iati_Registry($registryInfo->publisher_id , $registryInfo->api_key);
                            $organisationpublishedModel = new Model_OrganisationPublished();
                            $files = $organisationpublishedModel->getPublishedInfo($account_id);

                            foreach ($files as $file)
                            {
                                $reg->prepareOrganisationRegistryData($file);
                                $reg->publishToRegistry();
                            }

                            if ($reg->getErrors())
                            {
                                $this->_helper->FlashMessenger->addMessage(array('message' => 'Organisation xml files created. ' . $reg->getErrors()));
                            } else
                            {
                                $this->_helper->FlashMessenger->addMessage(array('message' => "Organisation published to IATI registry."));
                            }
                        }
                    } else
                    {
                        $this->_helper->FlashMessenger->addMessage(array('message' => "Organisation xml files created."));
                    }
                }
            } else
            {
                $db->updateOrganisationState($organisationIds , (int) $state);
            }
        }
        $this->_redirect("organisation/view-elements/?parentId=$organisationIds[0]");

    }
    
    /**
     * Delete Published Files As Selected 
     */
    public function deletePublishedFileAction()
    {
        $fileId = $this->_getParam('file_id');
        $OrganisationPublishedModel = new Model_OrganisationPublished();
        $publishedFiles = $OrganisationPublishedModel->deleteByFileId($fileId);

        $this->_helper->FlashMessenger->addMessage(array('message' => "File Deleted Sucessfully."));
        $this->_redirect('wep/list-published-files');

    }
    
    /**
     * Registered Published Files As Selected 
     */
    public function publishInRegistryAction()
    {
        $fileIds = explode(',' , $this->_getParam('organisation_file_ids'));
       
        if(!$fileIds[0]){
            $this->_helper->FlashMessenger->addMessage(array('message' => "Please select a file to register in IATI Registry."));
            $this->_redirect('wep/list-published-files');
        }
        $identity = Zend_Auth::getInstance()->getIdentity();
        $accountId = $identity->account_id;
        $modelRegistryInfo = new Model_RegistryInfo();
        $registryInfo = $modelRegistryInfo->getOrgRegistryInfo($accountId);

        if(!$registryInfo->api_key){
            $this->_helper->FlashMessenger->addMessage(array('error' => "Api Key not found. Organisation could not be registered in IATI Registry. Please go to <a href='{$this->view->baseUrl()}/wep/edit-defaults'>Change Defaults</a> to add API key."));
        } else {
            $reg = new Iati_Registry($registryInfo->publisher_id , $registryInfo->api_key ,true);
            $organisationPublishedModel = new Model_OrganisationPublished();
            $files = $organisationPublishedModel->getPublishedInfoByIds($fileIds);

            foreach($files as $file){
                $reg->prepareOrganisationRegistryData($file);
                $reg->publishToRegistry();
            }

            if($reg->getErrors()){
                $this->_helper->FlashMessenger->addMessage(array('error' => $reg->getErrors()));
            } else {
                $this->_helper->FlashMessenger->addMessage(array('message' => "Organisation registered to IATI registry."));
            }
        }

        $this->_redirect('wep/list-published-files');
    }

    /**
     * Update Default Element Value Of An Organisation
     */
    public function updateDefaultAction()
    {
        $elementName = $this->getRequest()->getParam('elementName');
        $elementId = $_POST['id'];
        
        if (!$elementName)
        {
            $this->_helper->FlashMessenger->addMessage(array('error' => "Could not fetch element Name."));
            $this->_redirect("/wep/dashboard");
        }

        if (!$elementId)
        {
            $this->_helper->FlashMessenger->addMessage(array('error' => "No id provided."));
            $this->_redirect("/wep/dashboard");
        }

        // Update Default Element Value And Fetch Organisation Id
        $organisationDefaultElementModel = new Model_OrganisationDefaultElement();
        $organisationId = $organisationDefaultElementModel->updateElementData($elementName , $elementId);

        //Update Organisation Hash
        $organisationHashModel = new Model_OrganisationHash();
        $updated = $organisationHashModel->updateHash($organisationId);
        if (!$updated)
        {
            $type = 'message';
            $message = "Already up to date. To make changes please change values in 'Change Defaults' and then update.";
        } else
        {
            //Update the organisation so that the last updated time is updated
            $wepModel = new Model_Wep();
            $organisationData = array();
            $organisationData['@last_updated_datetime'] = date('Y-m-d h:i:s');
            $organisationData['id'] = $organisationId;
            $wepModel->updateRowsToTable('iati_organisation' , $organisationData);

            //Change state to editing
            $db = new Model_OrganisationState();
            $db->updateOrganisationState($organisationId , Iati_WEP_ActivityState::STATUS_EDITING);
            $type = 'message';
            // Get display name
            $className = "Iati_Aidstream_Element_Organisation_$elementName";
            $elementClassObj = new $className;
            $displayName = $elementClassObj->getDisplayName();
            
            $message = "$displayName sucessfully updated";
        }
        $this->_helper->FlashMessenger->addMessage(array($type => $message));
        $this->_redirect("/organisation/edit-elements/?parentId=" . $organisationId . "&className=Organisation_$elementName");

    }

}