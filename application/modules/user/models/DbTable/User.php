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

}

?>
