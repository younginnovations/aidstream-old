<?php

class Model_RegistryInfo extends Zend_Db_Table_Abstract
{
    protected $_name = 'registry_info';
    
    public function saveRegistryInfo($data)
    {
        $this->insert($data);
    }
    
    public function updateRegistryInfo($data)
    {
        if($this->checkHasRegistryInfo($data['org_id'])){
            $this->update($data,array('org_id = ?' => $data['org_id']));
        } else {
            $this->saveRegistryInfo($data);
        }
    }
    
    public function getOrgPublishingType($orgId)
    {
        $row = $this->select()
            ->from($this->_name,'publishing_type')
            ->where('org_id = ?',$orgId);
        return $this->fetchRow($row)->publishing_type;
    }
    
    public function getOrgRegistryInfo($orgId)
    {
        $row = $this->select()
            ->where('org_id = ?', $orgId);
        return $this->fetchRow($row);
    }
    
    public function checkHasRegistryInfo($orgId)
    {
        $row = $this->select()
            ->from($this->_name,'id')
            ->where('org_id = ?' , $orgId);
        return ($this->fetchRow($row))?true:false;
    }
    
    public function deleteRegistryInfo($orgId)
    {
        $this->delete(array('org_id = ?' => $orgId));
    }
}