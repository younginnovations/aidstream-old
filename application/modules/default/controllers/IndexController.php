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
    public function organisationsAction()
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

    public function organisationAction() 
    {
        if ($_GET['reporting_org']) {
            $reportingOrg = $this->_request->getParam('reporting_org');
        } else {
            $this->redirect('organisation?reporting_org=all');
        }
        (!$reportingOrg) ? ($handler = new Iati_Snapshot_Lib_DataHandler()) : ($handler = new Iati_Snapshot_Lib_DataHandler($reportingOrg));
        $accountModel = new User_Model_DbTable_Account();
        $userModel = new Model_User();
        $publishModel = new Model_Published();
        $wepModel = new Model_Wep();
        $regInfoModel = new Model_RegistryInfo();
        
        $result = $accountModel->getAccountByOrganisation($reportingOrg);
        if (count($result)) {
            // Get Account Id
            $accountId = $result['id'];
            $user = $userModel->getUserByAccountId($accountId);
            $regInfo = $regInfoModel->getOrgRegistryInfo($accountId);
        
            // Get Organisation Info
            $organisation_array['name'] = $result['name'];
            $organisation_array['image'] = $result['file_name'];
            $organisation_array['address'] = $result['address'];
            $organisation_array['email'] = $user['email'];
            $organisation_array['telephone'] = ($result['telephone']) ? $result['telephone'] : 'Not Available';
            $organisation_array['website'] = ($result['url']) ? $result['url'] : 'Not Available';
            $organisation_array['twitter'] = ($result['twitter']) ? $result['twitter'] : 'Not Available';
            $organisation_array['prefix'] = $result['username'];
            
            $this->view->organisation_array = $organisation_array;
            $this->view->registry_url = Zend_Registry::get('config')->registry
                                            ."/publisher/".$regInfo->publisher_id;
        } else {
            // For all organisations: snapshot 
            if ($reportingOrg == 'all' || $reportingOrg == '') {
                $activityModel = new Model_Activity();
                $orgData = $activityModel->allOrganisationsActivityStates();
                foreach ($orgData as $key => $row) {
                    $total['activities'] += array_sum($row['states']);
                    $total['published'] +=  $row['registry_published_count'];
                }
                $accountModel = new User_Model_DbTable_Account();
                $count = $accountModel->getAccountCount();
                $total['organisations'] = $count['total'];
                $this->view->total = $total;
            } else {
                $this->redirect('organisation?reporting_org=all');
            }
        } // end if
        $this->view->handler = $handler;
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