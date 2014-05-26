<?php

class User_Model_DbTable_User extends Zend_Db_Table_Abstract {

    protected $_name = 'user';

    public function checkUnique($email)
    {

        $select = $this->select()->where('email = ?', $email);
        $row = $this->fetchRow($select);
        if($row) {
            return FALSE;
        }
        else {
            return TRUE;
        }

    }

    public function insert($userdata) {
        $data['user_name'] = $userdata['user_name'];
        $data['role_id'] = $userdata['role_id'];
        $data['email'] = $userdata['email'];
        $data['password'] = $userdata['password'];
        //        $data['mobile'] = $userdata['mobile'];

        return parent::insert($data);
    }

    public function getUserById($id) {
        $query = $this->select()->where('user_id = ?',$id);
        return $this->fetchRow($query);
    }

    public function getUserByUsername($username){
        $query = $this->select()->where('user_name = ?',$username);
        return $this->fetchRow($query);
    }

    public function changePassword($user,$id) {
        $data['password'] = md5($user['password']);

        return parent::update($data,array('user_id = ?'=>$id));
    }

    public function editUser($user,$id) {
        $data['user_name'] = $user['user_name'];
        $data['email'] = $user['email'];
        //                $data['mobile'] = $user['mobile'];

        return parent::update($data,array('user_id = ?'=>$id));
    }
    
    public function deleteUser($user_id)
    {
        $where = $this->getAdapter()->quoteInto('user_id = ?', $user_id);
        $this->delete($where);
    }
    
    public function getUserByEmail($email)
    {
        $select = $this->select()->where('email = ?', $email);
        return $this->fetchRow($select);
    }
    
    public function updateUserByEmail($data , $email)
    {
        return $this->update($data ,  array('email = ?' => $email));
    }

  	public function updateUser($data, $user_id)
    {
        $value['email'] = $data['email'];
        return parent::update($value, array('user_id = ?' => $user_id));
    }

    public function changeAdminUsername($old_account_identifier, $account_identifier, $account_id)
    {
        $select = $this->select()
                    ->from($this,array('user_name'))
                    ->where('account_id = ?', $account_id); 
        $usernames = $this->fetchAll($select)->toArray();
        foreach ($usernames as $username) {
            $data = preg_replace('/' . $old_account_identifier . '/', $account_identifier, $username, 1);    
            parent::update($data, array('user_name = ?' => $username));
        }
        
        $accountModel = new User_Model_DbTable_Account();
        $accountModel->updateUsername($account_identifier, $account_id);
    } 

    public function changeGroupadminUsername($old_group_identifier, $group_identifier, $user_id) {
        $old_username = $old_group_identifier . '_group';
        $data['user_name'] = $group_identifier . '_group';
        parent::update($data, array('user_name = ?' => $old_username));

        $userGroupModel = new User_Model_DbTable_UserGroup();
        $userGroupModel->updateUsername($group_identifier, $user_id);
    }
}

?>
