<?php
class Model_User extends Zend_Db_Table_Abstract
{

    protected $_name = 'user';
    
    public function getAwaitingUser()
    {
        $select = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
        ->joinLeft(array('ac'=>'account'),'user.account_id = ac.id')
        ->where('role_id = ?', 1)
        ->where('status = ?',0);
        $row = $this->fetchAll($select);
        return $row;
    }
    
    public function updateStatus($status,$id) {
        foreach($status as $key => $val){
            $data[$key] = $val;
        }
        return parent::update($data,array('user_id = ?' => $id));
    }
}