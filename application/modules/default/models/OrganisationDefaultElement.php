<?php
class Model_OrganisationDefaultElement extends Zend_Db_Table_Abstract
{
    protected $_name ;
    
    public function updateElementData($elementName,$elementId)
    {  
        if($elementName == 'ReportingOrg')
        {   
            $tableName = "iati_organisation/reporting_org";
            $this->updateReportingOrgData($tableName,$elementId);
        }
        elseif($elementName == 'Identifier')
        {
            $tableName = "iati_organisation/identifier";
            $this->updateIdentifierData($tableName,$elementId);
        }
        $organisationId = $this->getOrganisationIdByElementId($tableName,$elementId);
        return $organisationId;
    }
    
    public function updateReportingOrgData($tableName,$elementId)
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $wepModel = new Model_Wep();

        $defaultFieldsValues = $wepModel->getDefaults('default_field_values', 'account_id', $identity->account_id);
        $defaults = $defaultFieldsValues->getDefaultFields();
        
        $reporting_org = array();
        $reporting_org['id'] = $elementId;
        $reporting_org['@ref'] = $defaults['reporting_org_ref'];
        $reporting_org['text'] = $defaults['reporting_org'];
        $reporting_org['@type'] = $defaults['reporting_org_type'];
        $reporting_org['@xml_lang'] = $defaults['reporting_org_lang'];
        
        $wepModel->updateRowsToTable($tableName,$reporting_org);
    }
    
    public function updateIdentifierData($tableName,$elementId)
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $wepModel = new Model_Wep();

        $defaultFieldsValues = $wepModel->getDefaults('default_field_values', 'account_id', $identity->account_id);
        $defaults = $defaultFieldsValues->getDefaultFields();
        
        $identifier = array();
        $identifier['id'] = $elementId;
        $identifier['text'] = trim($defaults['reporting_org_ref']);
        
        $wepModel->updateRowsToTable($tableName,$identifier);
    }
    
    public function getOrganisationIdByElementId($tableName,$id)
    {    
        $this->_name = $tableName;
        $row = $this->fetchRow(array('id = ?'=>$id));
        return $row->organisation_id;
    }
}
