<?php

class Model_Test
{

    public function required()
    {
        $userRole = new App_UserRole();
        $resource = new App_Resource();

        $resource->ownerUserId = $userRole->userId;

        if (Zend_Registry::get('acl')->isAllowed($userRole, $resource, 'edit-activity-elements')) {
            echo 'Model level test:: User is allowed to use the resource';
        }
        else
            echo 'Model level test:: User is not allowed to use the resource';
        return $userRole->userRole;
    }

}