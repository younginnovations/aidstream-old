<?php

class User_Model_DbTable_Profile extends Zend_Db_Table_Abstract {

    protected $_name = 'profile';

    public function getProfileByUserId($id){
        $query = $this->select()->where('user_id = ?',$id);
        return $this->fetchRow($query);
    }
    
    public function deleteProfile($user_id){
        $where = $this->getAdapter()->quoteInto('user_id = ?', $user_id);
        $this->delete($where);
    }
    
    public function updateProfile($data, $user_id){
        $value['first_name'] = $data['first_name'];
        $value['middle_name'] = $data['middle_name'];
        $value['last_name'] = $data['last_name'];
        return parent::update($value, array('user_id = ?' => $user_id));
    }
}