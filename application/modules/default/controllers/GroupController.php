<?php  
	class GroupController extends Zend_Controller_Action 
	{
		public function init() {
			$this->_helper->layout()->setLayout('layout_wep');
			$this->view->blockManager()->enable('partial/dashboard.phtml');
			$this->view->blockManager()->enable('partial/groupadmin-menu.phtml');
		}

		public function indexAction() {
			// Index Action
		}

		public function dashboardAction() {
			
		}

		public function listOrganisationAction() {
			$identity = Zend_Auth::getInstance()->getIdentity();
			$userId = $identity->user_id;
			
			$userModel = new Model_User();
			$groupModel = new User_Model_DbTable_Group();
			$userGroupModel = new User_Model_DbTable_UserGroup();
			$activityModel = new Model_ActivityCollection();
			
			$group = $userGroupModel->getRowByUserId($userId);
			$orgs = $groupModel->getAllOrganisationsByGroupId($group['id']);
			$org_data = array();

			foreach($orgs as $organisation)
			{
			    $users = $userModel->getUserCountByAccountId($organisation['account_id']);
			    $organisation['users_count'] = $users[0]['users_count'];
			    $activities = $activityModel->getActivitiesCountByAccount($organisation['account_id']);
			    $organisation['activity_count'] = $activities[0]['activity_count'];
			    $user = $userModel->getUserByAccountId($organisation['account_id'],array('role_id'=>1));
			    $organisation['user_id'] = $user['user_id'];
			    $org_data[] = $organisation;
			}

			$this->view->groupRow = $group;
			$this->view->rowSet = $org_data;
		}
	}
?>