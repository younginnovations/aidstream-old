<?php

class Model_Registry extends Zend_Db_Table_Abstract
{
    protected $_name = 'Iati_Registry';
    
    protected function _insertRegistryInfo($data)
    {
        return parent::insert($data);
    }
    
    public function saveRegistryInfo($data)
    {
        //delete the existing publish info
        $where = $this->getAdapter()->quoteInto('publishing_org_id = ?', $data['publishing_org_id']);
        $this->delete($where);
        //create new info
        $this->_insertRegistryInfo($data);
    }
    
    public function getPublishedInfo($account_id)
    {
        $rowSet = $this->select()->where("publishing_org_id = ?",$account_id);
        $result = $this->fetchAll($rowSet)->toArray();
        return $result;
    }
    
    public function getInfo()
    {
        
    }
    
}