<?php
class Model_Organisation extends Zend_Db_Table_Abstract
{

    protected $_name = 'iati_organisation';
    
    public function createOrganisation($orgId , $default , $iatiIdentifier)
    {  
        $wepModelObj = new Model_Wep();
        // Save Organization
        $organisationInfo['@xml_lang'] = $default['language'];        
        $organisationInfo['@last_updated_datetime'] = date('Y-m-d H:i:s');
        $organisationInfo['@default_currency'] = $default['currency'];
        $organisationInfo['account_id'] = $orgId;
        $organisationId = $wepModelObj->insertRowsToTable('iati_organisation', $organisationInfo);
        
        //Save reporting org
        $reporting_org = array();
        $reporting_org['@ref'] = $default['reporting_org_ref'];
        $reporting_org['text'] = $default['reporting_org'];
        $reporting_org['@type'] = $default['reporting_org_type'];
        $reporting_org['@xml_lang'] = $default['reporting_org_lang'];
        $reporting_org['organisation_Id'] = $organisationId;
        $reporting_org_id = $wepModelObj->insertRowsToTable('iati_organisation/reporting_org', $reporting_org);

        //Save Iati Identifier
        $iati_identifier = array();
        $iati_identifier['text'] = trim($iatiIdentifier['organisation_identifier']);
        $iati_identifier['organisation_Id'] = $organisationId;
        $iati_identifier_id = $wepModelObj->insertRowsToTable('iati_organisation/identifier', $iati_identifier);

        return (int) $organisationId;
    }
    
    public function checkOrganisationPresent($account_id)
    {
        $rowSet = $this->select()->where("account_id = ?",$account_id);
        $result = $this->fetchRow($rowSet);
        if($result)
        {   
            $result = $result->toArray();
            return $result['id'];
        }
        
        return $result;
    }

}