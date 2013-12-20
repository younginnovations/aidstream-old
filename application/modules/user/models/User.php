<?php

class User_Model_User
{
    /**
     * Function to create a new account.
     *
     * This function creates a new account, admin user for the account, admin user's profile and sets default values for
     * the admin user. It also sends mail to the user and the admin.
     * @param array $userData   array of account informatio.
     * @return int $accountId   id of the account created.
     */
    public function registerUser($userData)
    {

        $modelWep = new Model_Wep();
        $data = array();
        $data['email'] = $userData['email'];
        $data['first_name'] = $userData['first_name'];
        $data['last_name'] = $userData['last_name'];
        $data['account_identifier'] = $userData['account_identifier'];
        $data['user_name'] = $userData['user_name'];
        $data['password'] = $userData['password'];
        $data['org_name'] = $userData['org_name'];
        $data['org_address'] = $userData['org_address'];

        //Save Organisation Info
        $account['name'] = $data['org_name'];
        $account['address'] = $data['org_address'];
        $account['username'] = trim($data['account_identifier']);
        $account['uniqid'] = md5(date('Y-m-d H:i:s'));
        $accountId = $modelWep->insertRowsToTable('account', $account);

        //Save User Info
        $user['user_name'] = trim($data['user_name']);
        $user['password'] = md5($data['password']);
        $user['role_id'] = 1;
        $user['email'] = $data['email'];
        $user['account_id'] = $accountId;
        $user['status'] = 1;
        $user_id = $modelWep->insertRowsToTable('user', $user);

        //Save User Profile
        $admin['first_name'] = $data['first_name'];
        $admin['last_name'] = $data['last_name'];
        $admin['user_id'] = $user_id;
        $admin_id = $modelWep->insertRowsToTable('profile', $admin);

        //Create defaults
        $defaults = new Model_Defaults();
        $defaults->createDefaults($data , $accountId);
        
        //Send notification        
        $notification = new Model_Notification;
        $notification->sendRegistrationNotifications($data);

        return $accountId;
    }
}