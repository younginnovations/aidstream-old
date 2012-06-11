<?php
class Simplified_Model_Simplified
{
    public static function isSimplified()
    {
        $ses = new Zend_Session_Namespace('simplified');
        if($ses->simplified){
            return true;
        } else {
            return false;
        }
        
    }
    
    public static function addActivity($data , $default)
    {
        $model = new Model_Wep();

        //Save activity info
        $activityInfo['@xml_lang'] = $default['language'];
        $activityInfo['@default_currency'] = $default['currency'];
        $activityInfo['@hierarchy'] = $default['hierarchy'];
        $activityInfo['@last_updated_datetime'] = date('Y-m-d H:i:s');
        $activityInfo['activities_id'] = $orgId;
        //$activityId = $model->insertRowsToTable('iati_activity', $activityInfo);

        //Save reporting org
        $reporting_org = array();
        $reporting_org['@ref'] = $default['reporting_org_ref'];
        $reporting_org['text'] = $default['reporting_org'];
        $reporting_org['@type'] = $default['reporting_org_type'];
        $reporting_org['@xml_lang'] = $default['reporting_org_lang'];
        $reporting_org['activity_Id'] = $activityId;
        //$reporting_org_id = $model->insertRowsToTable('iati_reporting_org', $reporting_org);

        //Save Iati Identifier
        $iati_identifier = array();
        $iati_identifier['text'] = trim($iatiIdentifier['iati_identifier']);
        $iati_identifier['activity_identifier'] = trim($iatiIdentifier['activity_identifier']);
        $iati_identifier['activity_id'] = $activityId;
        //$iati_identifier_id = $model->insertRowsToTable('iati_identifier', $iati_identifier);
        
        //Save Activity title
        
        $model->insertRowsToTable('iati_title' , $data['title']);
    }
}
