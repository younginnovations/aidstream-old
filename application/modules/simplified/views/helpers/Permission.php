<?php
class Zend_View_Helper_Permission extends Zend_View_Helper_Abstract
{
    protected $tblName = 'default_user_field';
    function checkPermission($action = '')
    {
        $auth = Zend_Auth::getInstance();
        $identity = $auth->getIdentity();
        
        $wepModel = new Model_Wep();
        $permissions = $wepModel->getRowsByFields($this->tblName, 'user_id', $identity->user_id);
        
    }
}