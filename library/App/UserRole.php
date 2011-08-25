<?php
class App_UserRole implements Zend_Acl_Role_Interface
{
    // using public members here for brevity in this article

    /*
     * userId and Role can be fetched from Zend Auth
     *
     */

    public $userId = null;
    public $userRole = null;
    public $accountId = null;


    public function  __construct()
    {
        $this->userId = Zend_Auth::getInstance()->getIdentity()->user_id;
        $this->userRole = Zend_Auth::getInstance()->getIdentity()->role;
        $this->accountId = Zend_Auth::getInstance()->getIdentity()->account_id;
    }


    public function getRoleId()
    {
        return $this->userRole;
    }
}
