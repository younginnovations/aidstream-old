<?php

class User_Model_DbTable_UserGroup extends Zend_Db_Table_Abstract {

    protected $_name = 'user_group';

    public function insertUserGroup($data){
        return $this->insert($data);
    }

    public function getRowByUserId($userId) {
        $query = $this->select()->where('user_id = ?', $userId);
        return $this->fetchRow($query)->toArray();
    }

    public function getRowByGroupId($groupId) {
        $query = $this->select()->where('group_id = ?', $groupId);
        return $this->fetchRow($query)->toArray();
    }
    
    public function deleteUserGroup($groupId){
        $where = $this->getAdapter()->quoteInto('group_id = ?', $groupId);
        return $this->delete($where);
    }
    
    public function updateUserGroup($data, $groupId){
        return $this->update($data, array('group_id = ?' => $groupId));
    }

    public function getAllUserGroups() {
        $query = $this->select();
        return $this->fetchAll($query)->toArray();
    }
}