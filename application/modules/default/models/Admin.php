<?php

class Model_Admin
{
    public function deleteOrganisationById($orgId)
    {        
        $wepModel = new Model_Wep();
        // Delete account
        $wepModel->deleteRow('iati_activities' , 'account_id' , $orgId);
        $wepModel->deleteRow('account' , 'id' , $orgId);
        
        // Delete Users
        $userModel = new Model_User();
        $users = $userModel->getAllUsersByAccountId($orgId);
        foreach($users as $user){
            $this->deleteUserById($user['user_id']);
        }

        // Delete Activities
        $actCollModel = new Model_ActivityCollection();
        $activities = $actCollModel->getActivityIdsByAccount($orgId);
        foreach($activities as $activity){
            $activityModel = new Model_Activity();
            $activityModel->deleteActivityById($activity['id']);
        }
        
        // Delete Defaults
        $wepModel->deleteRow('default_field_groups' , 'account_id' , $orgId);
        $wepModel->deleteRow('default_field_values' , 'account_id' , $orgId);
        
        // Delete registry info
        $regModel = new Model_RegistryInfo();
        $regModel->deleteRegistryInfo($orgId);
    }
    
    public function deleteUserById($userId)
    {
        $userModel = new User_Model_DbTable_User();
        $userModel->deleteUser($userId);
        
        $profileModel = new User_Model_DbTable_Profile();
        $profileModel->deleteProfile($userId);
        
        $wepModel = new Model_Wep();
        $wepModel->deleteRow('user_permission', 'user_id', $userId);
        $wepModel->deleteRow('Privilege', 'owner_id', $userId);
    }
}