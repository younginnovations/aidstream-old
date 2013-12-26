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
    
    public function getRegistryInfoForCurrentAccount()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $info = $this->getOrgRegistryInfo($identity->account_id);
        if($info){
            return $info->toArray();
        } else {
            return arrray();
        }
    }
    
    public function updateRegistryInfoFromData($data)
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $registryInfo = array();
        $registryInfo['publisher_id'] = strtolower($data['publisher_id']);
        $registryInfo['api_key'] = $data['api_key'];
        $registryInfo['publishing_type'] = $data['publishing_type'][0];
        $registryInfo['update_registry'] = $data['update_registry'];
        $registryInfo['org_id'] = $identity->account_id;
        $this->updateRegistryInfo($registryInfo);
    }
}