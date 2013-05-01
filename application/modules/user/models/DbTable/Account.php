<?php
class User_Model_DbTable_Account extends Zend_Db_Table_Abstract {

    protected $_name = 'account';

    public function getAccountRowByUserName($tblName, $fieldName, $data){
        $this->_name = $tblName;
        $rowSet = $this->select()->where("$fieldName = ?",$data);
        $result = $this->fetchRow($rowSet);
        return $result;
    }

    public function updateFileNameWithNull($userName){
        $data['file_name'] = '';
        return parent::update($data, array('username = ?' => $userName));
    }

    public function updateAccount($data, $userName){
        $value['address'] = $data['address'];
        $value['url'] = $data['url'];
        return parent::update($value, array('username = ?' => $userName));
    }

    public function insertFileNameOrUpdate($data , $userName){
        $result['file_name'] = $data['file_name'];
        if($result['file_name']){
            return parent::update($result, array('username = ?' => $userName));
        }
    }

    public function getFileName($userName){
        $select = $this->select()->where('username=?', $userName);
        $value = $this->fetchRow($select);
        return $value;
    }
    public function getFileNamesForFooter()
    {
        $select = $this->select()
            ->from($this , array('file_name' , 'url'))
            ->where('file_name <> ""')
            ->where('display_in_footer = ?' , 1);
        return $this->fetchAll($select)->toArray();
    }
    
    public function getAccountCount()
    {
        $select = $this->select()
            ->from($this , array('total' =>'count(*)'));
        return $this->fetchRow($select)->toArray();
    }
}