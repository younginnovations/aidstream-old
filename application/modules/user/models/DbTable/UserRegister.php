<?php

class User_Model_DbTable_UserRegister extends Zend_Db_Table_Abstract
{
    protected $_name = 'user_register';
    
    public function saveRegisterInfo($data)
    {
        return $this->insert($data);
    }
}