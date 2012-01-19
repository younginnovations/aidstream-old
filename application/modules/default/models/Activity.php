<?php

class Model_Activity
{
    /**
     * Function to create an activity
     * 
     * An activity is created with the provided reporting org and iati identifier.
     * If default elements like default finance type are provided they are also created.
     * @param int $orgId    organisation id (activities id) of the organisation creating the activity
     * @param array $default    array of default values, default fields of an organisation
     * @param array $iatiIdentifier    array of iati identifier text and activity identifier
     * @return int $activityId  Activity id of the activity created
     */
    public function createActivity($orgId , $default , $iatiIdentifier)
    {
        $wepModel = new Model_Wep();
        //Save activity info
        $activityInfo['@xml_lang'] = $default['language'];
        $activityInfo['@default_currency'] = $default['currency'];
        $activityInfo['@hierarchy'] = $default['hierarchy'];
        $activityInfo['@last_updated_datetime'] = date('Y-m-d H:i:s');
        $activityInfo['activities_id'] = $orgId;
        $activityId = $wepModel->insertRowsToTable('iati_activity', $activityInfo);
        
        //Save reporting org
        $reporting_org = array();
        $reporting_org['@ref'] = $default['reporting_org_ref'];
        $reporting_org['text'] = $default['reporting_org'];
        $reporting_org['@type'] = $default['reporting_org_type'];
        $reporting_org['@xml_lang'] = $default['reporting_org_lang'];
        $reporting_org['activity_Id'] = $activityId;
        $reporting_org_id = $wepModel->insertRowsToTable('iati_reporting_org', $reporting_org);
        
        //Save Iati Identifier
        $iati_identifier = array();
        $iati_identifier['text'] = trim($iatiIdentifier['iati_identifier']);
        $iati_identifier['activity_identifier'] = trim($iatiIdentifier['activity_identifier']);
        $iati_identifier['activity_id'] = $activityId;
        $iati_identifier_id = $wepModel->insertRowsToTable('iati_identifier', $iati_identifier);
        
        //Save Iati Collaboration Type
        if($default['collaboration_type']){
            $collaborationType['@code'] = $default['collaboration_type'];
            $collaborationType['activity_id'] = $activityId;
            $collaborationType['@xml_lang'] = '';
            $collaborationType['text'] = '';
            $wepModel->insertRowsToTable('iati_collaboration_type', $collaborationType);  
        }
        
        //Save Iati Default Flow Type Type
        if($default['flow_type']){
            $flowType['@code'] = $default['flow_type'];
            $flowType['activity_id'] = $activityId;
            $flowType['@xml_lang'] = '';
            $flowType['text'] = '';
            $wepModel->insertRowsToTable('iati_default_flow_type', $flowType);
        }
        
        //Save Iati Default Finance Type Type
        if($default['finance_type']){
            $financeType['@code'] = $default['finance_type'];
            $financeType['activity_id'] = $activityId;
            $financeType['@xml_lang'] = '';
            $financeType['text'] = '';
            $wepModel->insertRowsToTable('iati_default_finance_type', $financeType);
        }
        
        //Save Iati Default Aid Type Type
        if($default['aid_type']){
            $aidType['@code'] = $default['aid_type'];
            $aidType['activity_id'] = $activityId;
            $aidType['@xml_lang'] = '';
            $aidType['text'] = '';
            $wepModel->insertRowsToTable('iati_default_aid_type', $aidType);   
        }
        
        //Save Iati Default Tied Status
        if($default['tied_status']){
            $tiedStatus['@code'] = $default['tied_status'];
            $tiedStatus['activity_id'] = $activityId;
            $tiedStatus['@xml_lang'] = '';
            $tiedStatus['text'] = '';
            $wepModel->insertRowsToTable('iati_default_tied_status', $tiedStatus);
        }
        
        return (int) $activityId;
    }
    
    public function deleteActivityById($activityId)
    {
        $dbLayer = new Iati_WEP_DbLayer();
        $dbLayer->deleteRows('Activity', 'id', $activityId);
        
        $modelActivityHash = new Model_ActivityHash();
        $modelActivityHash->deleteActivityHash($activityId);
    }
}