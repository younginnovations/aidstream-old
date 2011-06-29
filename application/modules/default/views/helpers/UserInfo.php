<?php
class Zend_View_Helper_UserInfo extends Zend_View_Helper_Abstract
{
    function userInfo()
    {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        return $identity;
    }
} 