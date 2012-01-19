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
        
        //Insert Default Values
        $defaultFieldsValues = new Iati_WEP_AccountDefaultFieldValues();                        
        $fieldString = serialize($defaultFieldsValues);
    
        $defaultValues['object'] = $fieldString;
        $defaultValues['account_id'] = $accountId;
        $defaultValuesId = $modelWep->insertRowsToTable('default_field_values', $defaultValues);
        
        //Insert Default Fields
        $defaultFieldGroup = new Iati_WEP_AccountDisplayFieldGroup();                        
        $default = array('title','description','activity_status','activity_date','participating_org','recipient_country','sector','budget','transaction');

        foreach ($default as $eachField) {
            $defaultFieldGroup->setProperties($eachField);
        }

        $fieldString = serialize($defaultFieldGroup);
        $defaultFields['object'] = $fieldString;
        $defaultFields['account_id'] = $accountId;
        $defaultFieldId = $modelWep->insertRowsToTable('default_field_groups', $defaultFields);
     
        //Send notification
        $to = array($data['email'] => '');
        $mailParams['subject'] = 'Account registration confirmed';
        $mailParams['first_name'] = $data['first_name'];
        $mailParams['middle_name'] = $data['middle_name'];
        $mailParams['last_name'] = $data['last_name'];
        $mailParams['username'] = $user['user_name'];
        $mailParams['password'] = $data['password'];
        $template = 'user-register.phtml';
        $Wep = new App_Notification;
        $Wep->sendemail($mailParams,$template,$to);
        
        return $accountId;
    }
}