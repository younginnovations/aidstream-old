<?php
/**
 * Controller to handle actions to organisation data. It handles all CRUD and other operations for organisation.
 * @author YIPL Dev team
 */

class ActivityController extends Zend_Controller_Action
{
    public function init()
    {
        $identity  = Zend_Auth::getInstance()->getIdentity();
        $this->_helper->layout()->setLayout('layout_wep');
        $this->view->blockManager()->enable('partial/dashboard.phtml');
        $this->view->blockManager()->enable('partial/primarymenu.phtml');
        $this->view->blockManager()->enable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->enable('partial/published-list.phtml');

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
        
    public function addElementAction()
    {
        $elementClass = $this->_getParam('className');
        $parentId = $this->_getParam('activity_id');
        $isMultiple = $this->_getParam('isMultiple');

        if(!$elementClass){
            $this->_helper->FlashMessenger->addMessage(array('error' => "Could not fetch element."));
            $this->_redirect("/wep/dashboard");           
        }
        
        $elementName =  "Iati_Aidstream_Element_".$elementClass;
        $element = new $elementName();
        if($isMultiple == '0'){
            $element->setIsMultiple(false); 
        }      
        
        if($data = $this->getRequest()->getPost()){
            $element->setData($data[$element->getClassName()]);
            $form = $element->getForm();
            if($form->validate()){
                $data = $this->getRequest()->getPost();
                $id = $element->save($data[$element->getClassName()] , $parentId);
                
                Model_Activity::updateActivityUpdatedInfo($parentId);
                $type = 'message';
                $message = $element->getDisplayName() . " successfully inserted.";
                $this->_helper->FlashMessenger->addMessage(array($type => $message));
                
                if($parentId){
                    $idParam = "parent_id={$parentId}";
                } else {
                    $idParam = "id={$id}";
                }
                if($element->getClassName() == "Transaction" || $element->getClassName() == "Result")
                {
                    $this->_redirect("activity/list-elements?classname={$elementClass}&activity_id={$parentId}");
                }
                if ($_POST['save_and_view'])
                {
                    $this->_redirect("activity/edit-element?classname={$elementClass}&activity_id={$parentId}");
                } 
                
            } else {
                $this->_helper->FlashMessenger->addMessage(array('error' => "You have some problem in your data. Please correct and save again"));
            }
            
        } else {
            $form = $element->getForm();            
        }
        $form->addSubmitButton('Save');
       
        $this->view->form = $form;
        $this->view->activityInfo = Model_Activity::getActivityInfo($parentId);
        $this->view->elementClass = $element->getClassName();
        $this->view->displayName = $element->getDisplayName();
        
        $this->view->blockManager()->enable('partial/activitymenu.phtml');
        $this->view->blockManager()->disable('partial/primarymenu.phtml');
        $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->disable('partial/usermgmtmenu.phtml');
        $this->view->blockManager()->disable('partial/published-list.phtml');
    }
    
    public function listElementsAction()
    {
        $elementClass = $this->_getParam('classname');
        $parentId = $this->_getParam('activity_id');

        if(!$elementClass){
            $this->_helper->FlashMessenger->addMessage(array('error' => "Could not fetch element."));
            $this->_redirect("/wep/dashboard");           
        }
        
        $elementName =  "Iati_Aidstream_Element_".$elementClass;
        $element = new $elementName();
        $data = $element->fetchData($parentId , true);
        
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
    }
    
    public function editElementAction()
    {
        $elementClass = $this->_getParam('className');
        $id = $this->_getParam('id');
        $activityId = $this->_getParam('activity_id');
        $parentId = $this->_getParam('parent_id');
        $isMultiple = $this->_getParam('isMultiple');
        
        if(!$elementClass){
            $this->_helper->FlashMessenger->addMessage(array('error' => "Could not fetch element."));
            $this->_redirect("/wep/dashboard");           
        }
        
        $elementName =  "Iati_Aidstream_Element_".$elementClass;
        $element = new $elementName();
        if($isMultiple == '0'){
            $element->setIsMultiple(false);
        }
        if($data = $this->getRequest()->getPost()){
            $element->setData($data[$element->getClassName()]);
            $form = $element->getForm(); 
            if($form->validate()){
                $data = $this->getRequest()->getPost();
                $element->save($data[$element->getClassName()] , $parentId);
                
                $activityHashModel = new Model_ActivityHash();
                $updated = $activityHashModel->updateActivityHash($activityId);
                if(!$updated){
                    $type = 'info';
                    $message = 'No Changes Made';
                } else {
                    Model_Activity::updateActivityUpdatedInfo($activityId);                        
                    $type = 'message';
                    $message = $element->getDisplayName() . " successfully updated.";
                }
                $this->_helper->FlashMessenger->addMessage(array($type => $message)); 
                if($element->getClassName() == "Transaction" || $element->getClassName() == "Result")
                {
                    $this->_redirect("activity/list-elements?classname={$elementClass}&activity_id={$activityId}");
                }
                
                // Check if save and view button was clicked
                $button = false;
                if($element->getIsMultiple())
                {
                    $button = $data['save_and_view'];
                }
                else
                {
                    $button = $data[$element->getClassName()]['save_and_view'];
                }
                if ($button)
                {
                    $this->_redirect('activity/view-activity-info/?activity_id=' . $activityId);
                }
                
            } else {
                $form->populate($data);
                $this->_helper->FlashMessenger->addMessage(array('error' => "You have some problem in your data. Please correct and save again"));
            }
        } else {
            if($parentId){
                $data[$element->getClassName()] = $element->fetchData($parentId , true);
            } else {
                if($id)
                {
                     $data = $element->fetchData($id);  
                }
                else
                {  
                    if($element->getDisplayName() == 'Activity Default')
                    { 
                        $data[$element->getClassName()] = $element->fetchData($activityId);  
                    } else
                    {    
                        $data[$element->getClassName()] = $element->fetchData($activityId,true);
                    }                  
                }    
            }
            if(empty($data[$element->getClassName()])){
                $this->_redirect("/activity/add-element?className={$elementClass}&activity_id={$activityId}");
            }
           
            $element->setData($data[$element->getClassName()]);
            $form = $element->getForm();
        }
        $form->addSubmitButton('Save');
        
        $this->view->form = $form;
        $this->view->activityInfo = Model_Activity::getActivityInfo($activityId);
        $this->view->elementClass = $element->getClassName();
        $this->view->displayName = $element->getDisplayName();
        
        $this->view->blockManager()->enable('partial/activitymenu.phtml');
        $this->view->blockManager()->disable('partial/primarymenu.phtml');
        $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->disable('partial/usermgmtmenu.phtml');
        $this->view->blockManager()->disable('partial/published-list.phtml');
    }
    
    public function deleteElementAction()
    {
        $elementClass = $this->_getParam('classname');
        $eleId = $this->_getParam('id');
        $activityId = $this->_getParam('activity_id');
        if(!$elementClass){
            $this->_helper->FlashMessenger->addMessage(array('error' => "Could not fetch element."));
            $this->_redirect("/wep/dashboard");           
        }
        
        if(!$eleId){
            $this->_helper->FlashMessenger->addMessage(array('error' => "No id provided."));
            $this->_redirect("/wep/dashboard");  
        }
        
        $elementName =  "Iati_Aidstream_Element_".$elementClass;
        $element = new $elementName();
        $element->deleteElement($eleId , false);
        
        $this->_helper->FlashMessenger->addMessage(array('message' => "Element Deleted sucessfully."));
        $this->_redirect("activity/list-elements?classname={$elementClass}&activity_id={$activityId}");
    }
    
    public function viewElementAction()
    {
        $this->_helper->layout()->disableLayout(); 
        
        $elementClass = $this->_getParam('classname');
        $eleId = $this->_getParam('id');
        
        $elementName =  "Iati_Aidstream_Element_".$elementClass;
        $element = new $elementName();
        $element->setIsMultiple(false);

        $data = $element->fetchData($eleId );
        $this->view->data = $data[$element->getClassName()];
        $this->view->className = $element->getClassName(); 
    }
    
    public function viewActivityInfoAction()
    {
        $activityId = $this->getRequest()->getParam('activity_id');

        // Fetch activity data
        $activityClassObj = new Iati_Aidstream_Element_Activity();
        $activities = $activityClassObj->fetchData($activityId , false);
        $this->view->activities = $activities;
        $this->view->parentId = $activityId;

        // Fetch title
        $wepModel = new Model_Wep();
        $title_row = $wepModel->getRowById('iati_title', 'activity_id', $activityId);
        $title = $title_row['text'];
        $this->view->title = $title;
       
        // Get form for status change
        $state = $activities['Activity']['status_id'];
        $next_state = Iati_WEP_ActivityState::getNextStatus($state);
        if ($next_state && Iati_WEP_ActivityState::hasPermissionForState($next_state))
        {
            $status_form = new Form_Wep_ActivityChangeState();
            $status_form->setAction($this->view->baseUrl() . "/wep/update-status");
            $status_form->ids->setValue($activityId);
            $status_form->status->setValue($next_state);
            $status_form->change_state->setLabel(Iati_WEP_ActivityState::getStatus($next_state));
        } else
        {
            $status_form = null;
        }
        $this->view->status_form = $status_form;
        $this->view->state = $state;

        $this->view->blockManager()->enable('partial/activitymenu.phtml');
        $this->view->blockManager()->disable('partial/primarymenu.phtml');
        $this->view->blockManager()->disable('partial/add-activity-menu.phtml');
        $this->view->blockManager()->disable('partial/usermgmtmenu.phtml');
        $this->view->blockManager()->disable('partial/published-list.phtml');

    }        
}