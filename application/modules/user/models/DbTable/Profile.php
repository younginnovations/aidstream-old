<?php

class User_Model_DbTable_Profile extends Zend_Db_Table_Abstract {

    protected $_name = 'profile';

    public function getProfileByUserId($id){
        $query = $this->select()->where('user_id = ?',$id);
        return $this->fetchRow($query);
    }
}