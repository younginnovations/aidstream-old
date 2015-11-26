<?php

/**
 * Controller to handle actions to activity data. It handles all CRUD and
 * other operations for activity.
 *
 * @author YIPL Dev team
 */
class ActivityController extends Zend_Controller_Action
{
    public function init()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $activityId = $this->_getParam('activity_id');

        // Check activity access
        if ($activityId) {
            $model = new Model_ActivityCollection();
            $access = $model->getActivityAccess($activityId, $identity->account_id);
            if (!$access) {
                $this->_helper->FlashMessenger
                    ->addMessage(array('error' => "Access denied."));
                $this->_redirect("/wep/dashboard");
            }
        }

        $this->_helper->layout()->setLayout('layout_wep');
        $this->view->blockManager()->enable('partial/dashboard.phtml');
        $this->view->blockManager()->enable('partial/primarymenu.phtml');
        $this->view->blockManager()->enable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->enable('partial/published-list.phtml');
        $this->view->blockManager()->enable('partial/organisation-data.phtml');
        $this->view->blockManager()->enable('partial/download-my-data.phtml');

        // for role user check if the user has permission to add, publish ,if not disable menu.
        if ($identity->role == 'user') {
            $model = new Model_Wep();
            $userPermission = $model->getUserPermission($identity->user_id);
            $permission = $userPermission->hasPermission(Iati_WEP_PermissionConts::ADD_ACTIVITY);
            $publishPermission = $userPermission->hasPermission(Iati_WEP_PermissionConts::PUBLISH);
            if (!$permission) {
                $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
            }
            if (!$publishPermission) {
                $this->view->blockManager()->disable('partial/published-list.phtml');
            }
        }
        $this->view->blockManager()->enable('partial/usermgmtmenu.phtml');
        if (!Simplified_Model_Simplified::isSimplified()) {
            $this->view->blockManager()->enable('partial/uploaded-docs.phtml');
        }
    }

    public function addElementAction()
    {
        $elementClass = $this->_getParam('className');
        $parentId = $this->_getParam('activity_id');
        $isMultiple = $this->_getParam('isMultiple', 1);

        if (!$elementClass) {
            $this->_helper->FlashMessenger
                ->addMessage(array('error' => "Could not fetch element."));
            $this->_redirect("/wep/dashboard");
        }

        $elementName = "Iati_Aidstream_Element_" . $elementClass;
        $element = new $elementName();
        if ($isMultiple == '0') {
            $element->setIsMultiple(false);
        }

        if ($data = $this->getRequest()->getPost()) {
            if (is_array($data['Transaction']['Sector'])) {
                foreach ($data['Transaction']['Sector'] as &$sector) {
                    if ($sector['vocabulary'] == 8) {
                        $sector['code'] = $sector['dac_three_code'];
                    } else if ($sector['vocabulary'] != 3) {
                        $sector['code'] = $sector['non_dac_code'];
                    }
                }
            }
            $element->setData($data[$element->getClassName()]);
            $form = $element->getForm();

            if ($form->validate()) {
                $hasData = $element->hasData($data[$element->getClassName()]);
                if (!$hasData) {
                    $this->_helper->FlashMessenger
                        ->addMessage(array('message' => "You have not entered any data."));
                    $this->_redirect("/activity/add-element?className={$elementClass}"
                        . "&activity_id={$parentId}&isMultiple={$isMultiple}");
                }
                $id = $element->save($data[$element->getClassName()], $parentId);

                Model_Activity::updateActivityUpdatedInfo($parentId);
                $type = 'message';
                $message = $element->getDisplayName() . " successfully inserted.";
                $this->_helper->FlashMessenger->addMessage(array($type => $message));

                if ($parentId) {
                    $idParam = "parent_id={$parentId}";
                } else {
                    $idParam = "id={$id}";
                }

                if (Iati_Aidstream_ElementSettings::isHandledIndividually($element->getClassName())) {
                    $this->_redirect("activity/list-elements?classname={$elementClass}"
                        . "&activity_id={$parentId}");
                }

                // Check if save and view button was clicked
                if ($data['save_and_view'] || $data[$element->getClassName()]['save_and_view']) {
                    $this->_redirect('activity/view-activity-info/?activity_id=' . $parentId);
                }
                $this->_redirect("activity/edit-element?className={$elementClass}"
                    . "&activity_id={$parentId}&isMultiple={$isMultiple}");

            } else {
                $this->_helper->FlashMessenger
                    ->addMessage(array('error' => "You have some problem in your
                                     data. Please correct and save again"));
            }

        } else {
            $form = $element->getForm();
        }
        if (Iati_Aidstream_ElementSettings::isHandledIndividually($element->getClassName())) {
            $form->addElement(
                'submit',
                'save',
                array(
                    'class' => 'form-submit',
                    'label' => 'Save ' . $element->getDisplayName()
                )
            );
        } else {
            $form->addSubmitButton('Save');
        }

        $this->view->form = $form;
        $this->view->activityInfo = Model_Activity::getActivityInfo($parentId);
        $this->view->elementClass = $element->getClassName();
        $this->view->displayName = $element->getDisplayName();

        $this->view->blockManager()->enable('partial/activitymenu.phtml');
        $this->view->blockManager()->disable('partial/primarymenu.phtml');
        $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->disable('partial/usermgmtmenu.phtml');
        $this->view->blockManager()->disable('partial/published-list.phtml');
        $this->view->blockManager()->disable('partial/organisation-data.phtml');
        $this->view->blockManager()->disable('partial/download-my-data.phtml');
        $this->view->blockManager()->disable('partial/uploaded-docs.phtml');
    }

    public function listElementsAction()
    {
        $elementClass = $this->_getParam('classname');
        $parentId = $this->_getParam('activity_id');

        if (!$elementClass) {
            $this->_helper->FlashMessenger->addMessage(array('error' => "Could not
                                                             fetch element."));
            $this->_redirect("/wep/dashboard");
        }

        $elementName = "Iati_Aidstream_Element_" . $elementClass;
        $element = new $elementName();
        $data = $element->fetchData($parentId, true);

        $this->view->data = $data;
        $this->view->activityId = $parentId;
        $this->view->elementClass = $elementClass;
        $this->view->className = $element->getClassName();
        $this->view->displayName = $element->getDisplayName();
        $this->view->activityInfo = Model_Activity::getActivityInfo($parentId);

        //$this->view->placeholder('title')->set($element->getClassName());

        $this->view->blockManager()->enable('partial/activitymenu.phtml');
        $this->view->blockManager()->disable('partial/primarymenu.phtml');
        $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->disable('partial/usermgmtmenu.phtml');
        $this->view->blockManager()->disable('partial/published-list.phtml');
        $this->view->blockManager()->disable('partial/organisation-data.phtml');
        $this->view->blockManager()->disable('partial/download-my-data.phtml');
        $this->view->blockManager()->disable('partial/uploaded-docs.phtml');
    }

    public function editElementAction()
    {
        $elementClass = $this->_getParam('className');
        $id = $this->_getParam('id');
        $activityId = $this->_getParam('activity_id');
        $parentId = $this->_getParam('parent_id');
        $isMultiple = $this->_getParam('isMultiple', 1);

        if (!$elementClass) {
            $this->_helper->FlashMessenger->addMessage(array('error' => "Could not
                                                             fetch element."));
            $this->_redirect("/wep/dashboard");
        }

        $elementName = "Iati_Aidstream_Element_" . $elementClass;
        $element = new $elementName();
        if ($isMultiple == '0') {
            $element->setIsMultiple(false);
        }
        if ($data = $this->getRequest()->getPost()) {
            if (is_array($data['Transaction']['Sector'])) {
                foreach ($data['Transaction']['Sector'] as &$sector) {
                    if ($sector['vocabulary'] == 8) {
                        $sector['code'] = $sector['dac_three_code'];
                    } else if ($sector['vocabulary'] != 3) {
                        $sector['code'] = $sector['non_dac_code'];
                    }
                }
            }
            $element->setData($data[$element->getClassName()]);
            $form = $element->getForm();

            if ($form->validate()) {
                //$eleId will be null is element is deleted or in case of db error
                $eleId = $element->save($data[$element->getClassName()], $activityId);

                $activityHashModel = new Model_ActivityHash();
                $updated = $activityHashModel->updateActivityHash($activityId);
                if (!$updated) {
                    $type = 'message';
                    $message = 'No Changes Made';
                } else {
                    $oldState = Model_Activity::getActivityStatus($activityId);
                    Model_Activity::updateActivityUpdatedInfo($activityId);
                    $type = 'message';
                    $message = $element->getDisplayName() . " successfully updated.";
                }
                $this->_helper->FlashMessenger->addMessage(array($type => $message));

                // In case of update notify the user about state change.
                if ($updated && $oldState != Iati_WEP_ActivityState::STATUS_DRAFT) {
                    $this->_helper->FlashMessenger
                        ->addMessage(array('state-change-flash-message' => "The
                                        activity state is changed back to Draft.
                                        You must complete and verify in order
                                        to publish the activity.")
                        );
                }

                if ($element->getClassName() == "Transaction" || $element->getClassName() == "Result") {
                    $this->_redirect("activity/list-elements?classname={$elementClass}"
                        . "&activity_id={$activityId}");
                }

                // Check if save and view button was clicked                
                if ($data['save_and_view'] || $data[$element->getClassName()]['save_and_view']) {
                    $this->_redirect('activity/view-activity-info/?activity_id=' . $activityId);
                }

                //In case the eleId is not present i.e the element is deleted redirect to add page.
                if (!$eleId) {
                    $this->_redirect("activity/add-element?className={$elementClass}"
                        . "&activity_id={$activityId}");
                }
                $this->_redirect("activity/edit-element?className={$elementClass}"
                    . "&activity_id={$activityId}");
            } else {
                $form->populate($data);
                $this->_helper->FlashMessenger
                    ->addMessage(array('error' => "You have some problem in your
                                        data. Please correct and save again")
                    );
            }
        } else {
            //This can be used to edit element at any level by providing parent id.
            if ($parentId) {
                $data[$element->getClassName()] = $element->fetchData($parentId, true);
            } else {
                if ($id) {
                    $data = $element->fetchData($id);
                } else {
                    if ($element->getClassName() == 'Activity') {
                        $data = $element->fetchData($activityId);
                    } else {
                        $data[$element->getClassName()] = $element->fetchData($activityId, true);
                    }
                }
            }
            if (empty($data[$element->getClassName()])) {
                $this->_redirect("/activity/add-element?className={$elementClass}"
                    . "&activity_id={$activityId}");
            }

            $element->setData($data[$element->getClassName()]);
            $form = $element->getForm();
        }
        /* @todo this part of code should be moved to base form or base element */

        if (Iati_Aidstream_ElementSettings::isHandledIndividually($element->getClassName())) {
            $form->addElement(
                'submit',
                'save',
                array(
                    'class' => 'form-submit',
                    'label' => 'Update ' . $element->getDisplayName()
                )
            );
        } else {
            $form->addSubmitButton('Save');
        }

        $this->view->form = $form;
        $this->view->activityInfo = Model_Activity::getActivityInfo($activityId);
        $this->view->elementClass = $element->getClassName();
        $this->view->displayName = $element->getDisplayName();

        $this->view->blockManager()->enable('partial/activitymenu.phtml');
        $this->view->blockManager()->disable('partial/primarymenu.phtml');
        $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->disable('partial/usermgmtmenu.phtml');
        $this->view->blockManager()->disable('partial/published-list.phtml');
        $this->view->blockManager()->disable('partial/organisation-data.phtml');
        $this->view->blockManager()->disable('partial/download-my-data.phtml');
        $this->view->blockManager()->disable('partial/uploaded-docs.phtml');
    }

    public function deleteElementAction()
    {
        $elementClass = $this->_getParam('classname');
        $eleId = $this->_getParam('id');
        $activityId = $this->_getParam('activity_id');
        if (!$elementClass) {
            $this->_helper->FlashMessenger
                ->addMessage(array('error' => "Could not fetch element."));
            $this->_redirect("/wep/dashboard");
        }

        if (!$eleId) {
            $this->_helper->FlashMessenger
                ->addMessage(array('error' => "No id provided."));
            $this->_redirect("/wep/dashboard");
        }

        $elementName = "Iati_Aidstream_Element_" . $elementClass;
        $element = new $elementName();
        $element->deleteElement($eleId, false);

        $this->_helper->FlashMessenger
            ->addMessage(array('message' => "Element Deleted sucessfully."));
        $this->_redirect("activity/list-elements?classname={$elementClass}"
            . "&activity_id={$activityId}");
    }

    public function deleteElementsAction()
    {
        if ($this->getRequest()->getPost()) {
            $elementClass = $this->_getParam('classname');
            $eleIds = $this->_getParam('id');
            $activityId = $this->_getParam('activity_id');

            if (!$elementClass) {
                $this->_helper->FlashMessenger
                    ->addMessage(array('error' => "Could not fetch element."));
                $this->_redirect("/wep/dashboard");
            }

            if (!count($eleIds)) {
                $this->_helper->FlashMessenger
                    ->addMessage(array('error' => "No id provided."));
                $this->_redirect("/wep/dashboard");
            }

            $elementName = "Iati_Aidstream_Element_" . $elementClass;
            $element = new $elementName();
            $eleIds = explode(",", $eleIds);

            foreach ($eleIds as $eleId) {
                $element->deleteElement($eleId, false);
            }

            $this->_helper->FlashMessenger
                ->addMessage(array('message' => 'Element(s) deleted successfully.'));
            $this->_redirect("activity/list-elements?classname={$elementClass}"
                . "&activity_id={$activityId}");
        } else {
            $this->_helper->FlashMessenger
                ->addMessage(array('error' => "Unknown action."));
            $this->_redirect("/wep/dashboard");
        }

    }

    public function viewElementAction()
    {
        $this->_helper->layout()->disableLayout();

        $elementClass = $this->_getParam('classname');
        $eleId = $this->_getParam('id');

        $elementName = "Iati_Aidstream_Element_" . $elementClass;
        $element = new $elementName();
        $element->setIsMultiple(false);

        $data = $element->fetchData($eleId);
        $this->view->data = $data[$element->getClassName()];
        $this->view->className = $element->getClassName();
    }

    public function viewActivityInfoAction()
    {
        $activityId = $this->getRequest()->getParam('activity_id');
        if (!$activityId) {
            $this->_helper->FlashMessenger->addMessage(array('error' => "No id provided."));
            $this->_redirect("/wep/dashboard");
        }

        // Fetch activity data
        $activityClassObj = new Iati_Aidstream_Element_Activity();
        $activities = $activityClassObj->fetchData($activityId, false);
        $this->view->activities = $activities;
        $this->view->parentId = $activityId;

        // Fetch title
        $activityInfo = Model_Activity::getActivityInfo($activityId);
        $this->view->activityInfo = $activityInfo;

        // Get form for status change
        $state = $activities['Activity']['status_id'];
        $next_state = Iati_WEP_ActivityState::getNextStatus($state);
        if ($next_state && Iati_WEP_ActivityState::hasPermissionForState($next_state)) {
            $status_form = new Form_Wep_ActivityChangeState();
            $status_form->setAction($this->view->baseUrl() . "/wep/update-status?redirect="
                . urlencode($this->getRequest()->getRequestUri()));
            $status_form->ids->setValue($activityId);
            $status_form->status->setValue($next_state);
            $status_form->change_state->setLabel(Iati_WEP_ActivityState::getStatus($next_state));
        } else {
            $status_form = null;
        }
        $this->view->status_form = $status_form;
        $this->view->state = $state;

        $this->view->blockManager()->enable('partial/activitymenu.phtml');
        $this->view->blockManager()->disable('partial/primarymenu.phtml');
        $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->disable('partial/usermgmtmenu.phtml');
        $this->view->blockManager()->disable('partial/published-list.phtml');
        $this->view->blockManager()->disable('partial/organisation-data.phtml');
        $this->view->blockManager()->disable('partial/download-my-data.phtml');
        $this->view->blockManager()->disable('partial/uploaded-docs.phtml');

    }

    public function deleteActivityAction()
    {
        $activityId = $this->_getParam('activity_id');

        if (!$activityId) {
            $this->_helper->FlashMessenger
                ->addMessage(array('error' => "No id provided."));
            $this->_redirect("/wep/dashboard");
        }

        $model = new Model_Activity();
        $model->deleteActivityById($activityId);

        $this->_helper->FlashMessenger
            ->addMessage(array('message' => "Activity Deleted sucessfully."));
        $this->_redirect("/wep/view-activities");

    }

    public function duplicateActivityAction()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $activityId = $this->_getParam('activity_id');
        if (!$activityId) {
            $this->_helper->FlashMessenger->addMessage(array('error' => "No id provided."));
            $this->_redirect('wep/view-activities');
        }

        $activityClassObj = new Iati_Aidstream_Element_Activity();
        $activityModel = new Model_Activity();
        $wepModel = new Model_Wep();

        $activities = $wepModel->listAll('iati_activities', 'account_id', $identity->account_id);
        $activities_id = $activities[0]['id'];
        $activityData = $activityClassObj->fetchData($activityId, false);

        $form = new Form_Wep_IatiIdentifier('add', $identity->account_id);
        $form->add('add', $identity->account_id);
        $form->populate(array('reporting_org' => $activityData['Activity']['ReportingOrg']['@ref']));

        if ($data = $this->getRequest()->getPost()) {
            if (!$form->isValid($data)) {
                $form->populate($data);
            } else {
                $iatiIdentifier = array();
                $iatiIdentifier['iati_identifier'] = $data['iati_identifier_text'];
                $iatiIdentifier['activity_identifier'] = $data['activity_identifier'];

                $newActivityId = $activityModel->duplicateActivity($activities_id, $activityId, $activityData['Activity'], $iatiIdentifier);
            }

            if ($newActivityId) {
                $this->_helper->FlashMessenger->addMessage(array('message' => "Activity duplication successful. View duplicated 
                    <a href='{$this->view->baseUrl()}/activity/view-activity-info/?activity_id={$newActivityId}'>activity</a>."));
                $this->_redirect('/wep/view-activities');
            } else {
                $this->_helper->FlashMessenger->addMessage(array('error' => 'Activity duplication failed.'));
                $this->_redirect('/wep/view-activities');
            }
        }

        $this->view->form = $form;
        $this->view->activity_info = $activityData['Activity'];
    }
}
