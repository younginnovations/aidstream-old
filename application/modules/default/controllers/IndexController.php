<?php
/**
 * Default controller. 
 * @author YIPL dev team
 */
class IndexController extends Zend_Controller_Action
{

    public function init()
    {
	    $this->_helper->layout->setLayout('layout_wep_index');   
    }
    /**
     * Home page
     */
    public function indexAction()
    {
    	$model = new User_Model_DbTable_Account();
    	$count = $model->getAccountCount();
    	
    	$this->view->orgCount = $count['total'];
    }
    
    /**
     * About us page.
     */
    public function aboutAction(){}
    
    /**
     * Users list page
     */
    public function usersAction()
    {
	    $model = new User_Model_DbTable_Account();
	    /* Moved to a new page from footer as per new home page*/
	    $usersWithImage = $model->getUsersWithFileNames();
	    $usersWithoutImage = $model->getUsersWithoutFiles();
	    $count = $model->getAccountCount();
	    
	    $this->view->usersWithImage = $usersWithImage;
	    $this->view->usersWithoutImage = $usersWithoutImage;
	    $this->view->count = $count['total'];
    }

    public function userAction() 
    {
        $reportingOrg = $this->_request->getParam('reporting_org');
        (!$reportingOrg) ? ($handler = new Iati_Snapshot_Lib_DataHandler()) : ($handler = new Iati_Snapshot_Lib_DataHandler($reportingOrg));
        $accountModel = new User_Model_DbTable_Account();
        $userModel = new Model_User();
        $publishModel = new Model_Published();
        $wepModel = new Model_Wep();
        
        $result = $accountModel->getAccountByOrganisation($reportingOrg);
        if (count($result)) {
            // Get Account Id
            $accountId = $result['id'];
            $user = $userModel->getUserByAccountId($accountId);

            // Get Organisation Info
            $organisation_array['name'] = $result['name'];
            $organisation_array['image'] = $result['file_name'];
            $organisation_array['address'] = $result['address'];
            $organisation_array['email'] = $user['email'];
            $organisation_array['telephone'] = ($result['telephone']) ? $result['telephone'] : 'Not Available';
            $organisation_array['website'] = ($result['url']) ? $result['url'] : 'Not Available';
            $organisation_array['twitter'] = ($result['twitter']) ? $result['twitter'] : 'Not Available';
            $organisation_array['prefix'] = $result['username'];
            
            // List Activities
            $activities = $wepModel->listAll('iati_activities', 'account_id', $accountId);
            $activitiesId = $activities[0]['id'];
            $activityArray = $wepModel->listAll('iati_activity', 'activities_id', $activitiesId);
            $files = $publishModel->getPublishedInfo($accountId);   
            if ($file['pushed_to_registry'] == 1) {     //change here
                if ($activityArray) {
                    $index = 0;
                    foreach($activityArray as $key=>$activity){
                        $title = $wepModel->listAll('iati_title', 'activity_id', $activity['id']);
                        $identifier = $wepModel->listAll('iati_identifier', 'activity_id', $activity['id']);
                        $activity_array[$index]['title'] = ($title[0]['text'])?$title[0]['text']:'No title';
                        $activity_array[$index]['link'] = "http://www.iatiregistry.org/publisher/" . $result['username'];
                        $index++;
                    }
                }
            }
            $this->view->handler = $handler;
            $this->view->organisation_array = $organisation_array;
            $this->view->activity_array = $activity_array;
        } else {
            $this->_helper->FlashMessenger->addMessage(array('error' => "No such page."));
            $this->_redirect();
        }// end if
        
    }

    public function ajaxAction() 
    {
        // Disable Layout and View for this action
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();     
        
        // If Ajax Request
        if ($this->_request->isXmlHttpRequest()) {
            $reportingOrg = $_GET['reporting_org'];
            $ele = $_GET['ele'];
            (!$reportingOrg) ? ($handler = new Iati_Snapshot_Lib_DataHandler()) : ($handler = new Iati_Snapshot_Lib_DataHandler($reportingOrg));
            $activityStatus = $handler->get($ele, true);
            print_r($activityStatus);
            exit;
        } else {
            $this->_redirect();
        } // End if
    }

}