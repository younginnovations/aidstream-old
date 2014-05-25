<?php
class User_Model_DbTable_Account extends Zend_Db_Table_Abstract {

    protected $_name = 'account';

    public function getAccountRowByUserName($tblName, $fieldName, $data){
        $this->_name = $tblName;
        $rowSet = $this->select()->where("$fieldName = ?",$data);
        $result = $this->fetchRow($rowSet);
        return $result;
    }

    public function getAccountRowById($accountId) {
        $rowSet = $this->select()->where('id = ?', $accountId);
        $result = $this->fetchRow($rowSet);
        return $result;
    }

    public function updateFileNameWithNull($userName){
        $data['file_name'] = NULL;
        return parent::update($data, array('username = ?' => $userName));
    }

    public function updateAccount($data, $userName){
        $value['name'] = trim($data['name']);
        $value['address'] = $data['address'];
        $value['url'] = $data['url'];
        $value['telephone'] = $data['telephone'];
        $value['twitter'] = $data['twitter'];
        $value['disqus_comments'] = $data['disqus_comments'];
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
    public function getUsersWithFileNames()
    {
        $select = $this->select()
            ->from($this , array('name' , 'file_name' , 'url'))
            ->where('file_name <> ""')
            ->where('display_in_footer = ?' , 1)
            ->order('name');
        return $this->fetchAll($select)->toArray();
    }

    public function getUsersWithoutFiles()
    {
        $select = $this->select()
            ->from($this , array('name' , 'file_name' , 'url'))
            ->where('file_name IS NULL')
            ->where('display_in_footer = ?' , 1)
            ->order('name');
        return $this->fetchAll($select)->toArray();
    }

    public function getAccountCount()
    {
        $select = $this->select()
            ->from($this , array('total' =>'count(*)'));
        return $this->fetchRow($select)->toArray();
    }

    public function getAccountByOrganisation($reportingOrg)
    {
        $select = $this->select()
            ->where('name = ?', $reportingOrg);
        if ($this->fetchRow($select)) {
            return $this->fetchRow($select)->toArray();
        }
    }

    public function getOrganisationNameById($accountId) 
    {
        $select = $this->select()
            ->from($this, array('name'))
            ->where('id = ?', $accountId);
        return $this->fetchRow($select)->name;
    }

    public function updateUsername($username, $accountId)
    {
        return parent::update(array('username' => $username), array('id = ?' => $accountId));        
    }

    public function getAllOrganisationNameWithId() 
    {
        $select = $this->select()
            ->from($this, array('id', 'name'))
            ->where("name NOT LIKE '%test%'")
            ->order("name");
        return $this->fetchAll($select)->toArray();
    }
}

