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

    public function addElementsAction()
    {
        $elementClass = $this->_getParam('classname');
        $parentId = $this->_getParam('parent_id');

        if (!$elementClass)
        {
            $this->_helper->FlashMessenger->addMessage(array('error' => "Could not fetch element."));
            $this->_redirect("/wep/dashboard");
        }

        $elementName = "Iati_Aidstream_Element_" . $elementClass;
        $element = new $elementName();
 
        if ($data = $this->getRequest()->getPost())
        { 
            $element->setData($data[$element->getClassName()]);
            $form = $element->getForm();
            if ($form->validate())
            {
                $id = $element->save($data[$element->getClassName()],$parentId);
                  
                //Update Activity Hash
                $organisationHashModel = new Model_OrganisationHash();
                $updated = $organisationHashModel->updateHash($parentId);
                if (!$updated)
                {
                    $type = 'info';
                    $message = 'No Changes Made';
                } else
                {
                    //update the organisation so that the last updated time is updated
                    $wepModel = new Model_Wep();
                    $organisationData = array();
                    $organisationData['@last_updated_datetime'] = date('Y-m-d h:i:s');
                    $organisationData['id'] = $parentId;
                    $wepModel->updateRowsToTable('iati_organisation' ,$organisationData);

                    //change state to editing
                    $db = new Model_OrganisationState;
                    $db->updateOrganisationState($parentId , Iati_WEP_ActivityState::STATUS_EDITING);
                    $type = 'message';
                    $message = $element->getClassName()." successfully updated.";
                }
                $this->_helper->FlashMessenger
                        ->addMessage(array($type => $message));
                if ($parentId)
                {
                    $idParam = "parent_id={$parentId}";
                } else
                {
                    $idParam = "id={$id}";
                }
                if ($_POST['save_and_view'])
                {
                    $this->_redirect('organisation/view-elements?parent_id=' . $parentId);
                }
                $this->_redirect("/organisation/edit-elements?classname={$elementClass}&${idParam}");
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
        $this->view->parent_id = $parentId;

        $this->view->blockManager()->enable('partial/organisation-menu.phtml');
        $this->view->blockManager()->disable('partial/primarymenu.phtml');
        $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->disable('partial/usermgmtmenu.phtml');
        $this->view->blockManager()->disable('partial/published-list.phtml');
        $this->view->blockManager()->disable('partial/organisation-data.phtml');

    }

    public function editElementsAction()
    {
        $elementClass = $this->_getParam('classname');
        $eleId = $this->_getParam('id');
        $parentId = $this->_getParam('parent_id');

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
            $element->setData($data[$element->getClassName()]);
            $form = $element->getForm();
            if ($form->validate())
            {
                $element->save($data[$element->getClassName()] , $parentId);

                //Update Activity Hash
                $organisationHashModel = new Model_OrganisationHash();
                $updated = $organisationHashModel->updateHash($parentId);
                if (!$updated)
                {
                    $type = 'info';
                    $message = 'No Changes Made';
                } else
                {
                    //update the organisation so that the last updated time is updated
                    $wepModel = new Model_Wep();
                    $organisationData = array();
                    $organisationData['@last_updated_datetime'] = date('Y-m-d h:i:s');
                    $organisationData['id'] = $parentId;
                    $wepModel->updateRowsToTable('iati_organisation' ,$organisationData);

                    //change state to editing
                    $db = new Model_OrganisationState;
                    $db->updateOrganisationState($parentId , Iati_WEP_ActivityState::STATUS_EDITING);
                    $type = 'message';
                    $message = $element->getClassName()." successfully updated.";
                }
                $this->_helper->FlashMessenger
                        ->addMessage(array($type => $message));

                if ($_POST['save_and_view'])
                {
                    $this->_redirect('organisation/view-elements?parent_id=' . $parentId);
                }
            } else
            {
                $form->populate($data);
                $this->_helper->FlashMessenger->addMessage(array('error' => "You have some problem in your data. Please correct and save again"));
            }
        } else
        {
            if ($parentId)
            {
                $data[$element->getClassName()] = $element->fetchData($parentId , true);
            } else
            {
                $data = $element->fetchData(array($eleId));
            }
            if (empty($data[$element->getClassName()]))
            {
                $this->_helper->FlashMessenger->addMessage(array('info' => "Data not found for the element. Please add new data"));
                $this->_redirect('/organisation/add-elements?classname=' . $elementClass . '&parent_id=' . $parentId);
            }
            $element->setData($data[$element->getClassName()]);
            $form = $element->getForm();
        }

        if ($element->getClassName() == 'ReportingOrg' || $element->getClassName() == Identifier)
        {
            $form->addElement('submit' , 'Update');
        } else
        {
            $form->addSubmitButton('Save');
        }

        $this->view->form = $form;

        // Fetch Title
        $wepModel = new Model_Wep();
        $reportingOrg = $wepModel->getRowsByFields('iati_organisation/reporting_org' , 'organisation_id' , $parentId);
        $title = $reportingOrg[0]['text'];
        $this->view->title = $title . " Organisation File";

        //Set organisation id to view 
        $this->view->parent_id = $parentId;

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

    public function organisationDataAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $account_id = $identity->account_id;

        $organisationModelObj = new Model_Organisation();
        $organisation_id = $organisationModelObj->checkOrganisationPresent($account_id);
        if (!$organisation_id)
        {
            $this->_redirect('organisation/add-organisation');
        }
        $this->_redirect('organisation/view-elements?parent_id=' . $organisation_id);

    }

    public function addOrganisationAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();

        $model = new Model_Viewcode();
        $rowSet = $model->getRowsByFields('default_field_values' , 'account_id' , $identity->account_id);

        $defaultValues = unserialize($rowSet[0]['object']);
        $default = $defaultValues->getDefaultFields();
        $wepModel = new Model_Wep();

        $reporting_org_info['@reporting_org_name'] = $default['reporting_org'];
        $reporting_org_info['@reporting_org_ref'] = $default['reporting_org_ref'];
        $reporting_org_info['@reporting_org_type'] = $wepModel->fetchValueById('OrganisationType' , $default['reporting_org_type'] , 'Code');
        $reporting_org_info['@reporting_org_lang'] = $wepModel->fetchValueById('Language' , $default['reporting_org_lang'] , 'Name');

        $iatiIdentifier['organisation_identifier'] = $default['reporting_org_ref'];

        $organisationModel = new Model_Organisation();
        $organisation_id = $organisationModel->createOrganisation($identity->account_id , $default , $iatiIdentifier);

        //Create Activity Hash
        $organisationHashModel = new Model_OrganisationHash();
        $updated = $organisationHashModel->updateHash($organisation_id);

        $this->_redirect('organisation/view-elements?parent_id=' . $organisation_id);

    }

    public function viewElementsAction()
    {
        $organisation_id = $this->getRequest()->getParam('parent_id');

        // Fetch Organisation Data
        $organisationClassObj = new Iati_Aidstream_Element_Organisation();
        $organisations = $organisationClassObj->fetchData($organisation_id , false);
        $this->view->organisations = $organisations;

        // Fetch Title
        $wepModel = new Model_Wep();
        $reportingOrg = $wepModel->getRowsByFields('iati_organisation/reporting_org' , 'organisation_id' , $organisation_id);
        $title = $reportingOrg[0]['text'];
        $this->view->title = $title . " Organisation File";

        // Get form for status change
        $state = $organisations['Organisation']['state_id'];
        $next_state = Iati_WEP_ActivityState::getNextStatus($state);
        if($next_state && Iati_WEP_ActivityState::hasPermissionForState($next_state)){
            $status_form = new Form_Wep_ActivityChangeState();
            $status_form->setAction($this->view->baseUrl()."/organisation/update-state");
            $status_form->ids->setValue($organisation_id);
            $status_form->status->setValue($next_state);
            $status_form->change_state->setLabel(Iati_WEP_ActivityState::getStatus($next_state));
        } else {
            $status_form = null;
        }
        $this->view->status_form = $status_form;
        $this->view->state = $organisations['Organisation']['state_id'];

        $this->view->blockManager()->enable('partial/organisation-menu.phtml');
        $this->view->blockManager()->disable('partial/primarymenu.phtml');
        $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->disable('partial/usermgmtmenu.phtml');
        $this->view->blockManager()->disable('partial/published-list.phtml');
        $this->view->blockManager()->disable('partial/organisation-data.phtml');

    }

    public function updateStateAction()
    {
        $ids = $this->getRequest()->getParam('ids');
        $state = $this->getRequest()->getParam('status');
        $organisation_ids = explode(',' , $ids);
        $db = new Model_OrganisationState;
        $not_valid = false;

        if ($not_valid)
        {
            $this->_helper->FlashMessenger->addMessage(array('warning' => "The organisation cannot be changed to the state. Please check that a state to be changed is valid for all selected activities"));
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
                    $db->updateOrganisationState($organisation_ids , (int) $state);

                    // Generate Xml
                    $obj = new Iati_Core_Xml();
                    $filename = $obj->generateXml('organisation' , $organisation_ids);


                    if ($registryInfo->update_registry)
                    {
                        if (!$registryInfo->api_key)
                        {
                            $this->_helper->FlashMessenger->addMessage(array('error' => "Api Key not found. Activities could not be registered in IATI Registry. Please go to <a href='{$this->view->baseUrl()}/wep/edit-defaults'>Change Defaults</a> to add API key."));
                        } else
                        {
                            $reg = new Iati_Registry($registryInfo->publisher_id , $registryInfo->api_key);
                            $modelPublished = new Model_Published();
                            $files = $modelPublished->getPublishedInfo($account_id);

                            foreach ($files as $file)
                            {
                                $reg->prepareOrganisationRegistryData($file);
                                $reg->publishToRegistry();
                            }

                            if ($reg->getErrors())
                            {
                                $this->_helper->FlashMessenger->addMessage(array('info' => 'Activities xml files created. ' . $reg->getErrors()));
                            } else
                            {
                                $this->_helper->FlashMessenger->addMessage(array('message' => "Activities published to IATI registry."));
                            }
                        }
                    } else
                    {
                        $this->_helper->FlashMessenger->addMessage(array('message' => "Organisation xml files created."));
                    }
                }
            } else
            {
                $db->updateOrganisationState($organisation_ids , (int) $state);
            }
        }
        $this->_redirect('organisation/view-elements');

    }

}