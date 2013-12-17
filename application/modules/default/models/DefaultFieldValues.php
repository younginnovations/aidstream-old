<?php
/**
 * Model to retrive default fields for the organisation.
 */
class Model_DefaultFieldValues extends Zend_Db_Table_Abstract
{
    protected $_name = 'default_field_values';
    
    /**
     * Function to retrieve the default values by organisation id(account id).
     * @param int $orgId    Account id of the organisation.
     * @param string $field Name of the field to be retrived. Used to retrive a single field.
     * @return mixed        If field name is set the value of the field is returned else the complete default row
     * is returned.
     */
    public function getByOrganisationId($orgId , $field = false)
    {
        $rowSet = $this->select()
            ->where('account_id = ?',$orgId);
        $row = $this->fetchRow($rowSet);
        
        $objDefault = unserialize($row->object);
        $default = $objDefault->getDefaultFields();
        
        if($field){
            return $default[$field];
        }
        return $default;
    }
    
    public function getDefaultObjByOrganisation($orgId)
    {
        $rowSet = $this->select()
            ->where('account_id = ?',$orgId);
        $row = $this->fetchRow($rowSet);
        
        $objDefault = unserialize($row->object);
        if($objDefault){
            return $objDefault;
        } else {
            return false;
        }
    }
    
}