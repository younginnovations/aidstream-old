<?php
/**
 * Controller to handle actions to organisation data. It handles all CRUD and other operations for organisation.
 * @author YIPL Dev team
 */

class OrganisationController extends Zend_Controller_Action
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
    
    public function addAction()
    {
        $elementClass = $this->_getParam('classname');
        if(!$elementClass){
            echo "no class given";exit;
        }
        $elementName =  "Iati_Organisation_Element_".$elementClass;
        $element = new $elementName();
        if($data = $this->getRequest()->getPost()){
            $element->setData($data[$elementClass]);
            $form = $element->getForm();
            //echo "<pre>";print_r($form);exit;
            
        } else {
            $form = $element->getForm();            
        }
        $form->addSubmitButton('Save');
        $this->view->form = $form;
    }
}