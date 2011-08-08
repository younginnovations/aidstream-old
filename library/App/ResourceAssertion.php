<?php

class App_ResourceAssertion implements Zend_Acl_Assert_Interface
{

    /**
     * This assertion should receive the actual User and Resource objects.
     *
     * @param Zend_Acl $acl
     * @param Zend_Acl_Role_Interface $user
     * @param Zend_Acl_Resource_Interface $resource
     * @param $privilege
     * @return bool
     */
    public $resource;
    public $userId;
    public $userRole;

    public function __construct($resource)
    {
        if(Zend_Auth::getInstance()->getIdentity()){
    	$userId = Zend_Auth::getInstance()->getIdentity()->user_id;
        $userRole = Zend_Auth::getInstance()->getIdentity()->role;
        }
        $this->setResource($resource);
        $this->setUserId($userId);
        $this->setUserRole($userRole);
    }

    public function getUserRole()
    {
        return $this->userRole;
    }

    public function setUserRole($userRole)
    {
        $this->userRole = $userRole;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    public function assert(Zend_Acl $acl, Zend_Acl_Role_Interface $user = null, Zend_Acl_Resource_Interface $resource = null, $privilege = null)
    {
        // if role is superadmin, he can always modify a post or a comment or any resource as necessary
        if ($this->getUserRole() == 'superadmin') {
            return true;
        }

        $assertion = new App_AssertionCheck();
        $result = $assertion->resourceCheck($this->userId, $this->getResource());
        return $result;
    }

}

?>
