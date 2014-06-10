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
    public function createActivity($orgId, $default, $iatiIdentifier) {
        $wepModel = new Model_Wep();
        
        //Save activity info
        $activityInfo['@xml_lang'] = $default['language'];
        $activityInfo['@default_currency'] = $default['currency'];
        $activityInfo['@hierarchy'] = $default['hierarchy'];
        $activityInfo['@linked_data_uri'] = $default['linked_data_default'];
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
        if ($default['collaboration_type']) {
            $collaborationType['@code'] = $default['collaboration_type'];
            $collaborationType['activity_id'] = $activityId;
            $collaborationType['@xml_lang'] = '';
            $collaborationType['text'] = '';
            $wepModel->insertRowsToTable('iati_collaboration_type', $collaborationType);
        }
        
        //Save Iati Default Flow Type Type
        if ($default['flow_type']) {
            $flowType['@code'] = $default['flow_type'];
            $flowType['activity_id'] = $activityId;
            $flowType['@xml_lang'] = '';
            $flowType['text'] = '';
            $wepModel->insertRowsToTable('iati_default_flow_type', $flowType);
        }
        
        //Save Iati Default Finance Type Type
        if ($default['finance_type']) {
            $financeType['@code'] = $default['finance_type'];
            $financeType['activity_id'] = $activityId;
            $financeType['@xml_lang'] = '';
            $financeType['text'] = '';
            $wepModel->insertRowsToTable('iati_default_finance_type', $financeType);
        }
        
        //Save Iati Default Aid Type Type
        if ($default['aid_type']) {
            $aidType['@code'] = $default['aid_type'];
            $aidType['activity_id'] = $activityId;
            $aidType['@xml_lang'] = '';
            $aidType['text'] = '';
            $wepModel->insertRowsToTable('iati_default_aid_type', $aidType);
        }
        
        //Save Iati Default Tied Status
        if ($default['tied_status']) {
            $tiedStatus['@code'] = $default['tied_status'];
            $tiedStatus['activity_id'] = $activityId;
            $tiedStatus['@xml_lang'] = '';
            $tiedStatus['text'] = '';
            $wepModel->insertRowsToTable('iati_default_tied_status', $tiedStatus);
        }
        
        return (int)$activityId;
    }
    
    /**
     * Function to duplicate an activity
     *
     * An activity is duplicated with the provided iati identifier and activity data of the activity to be duplicated.
     * @param int $orgId    organisation id (activities id) of the organisation creating the activity
     * @param int $oldActivityId    id of the activity to be duplicated
     * @param array $activityData   complete fetch data (activity data) of old activity
     * @param array $iatiIdentifier    array of iati identifier text and activity identifier
     * @return int $activityId  Activity id of the activity created after duplication
     */
    public function duplicateActivity($orgId, $oldActivityId, $activityData, $iatiIdentifier) {
        $wepModel = new Model_Wep();

        $activityInfo['@xml_lang'] = $activityData['@xml_lang'];
        $activityInfo['@default_currency'] = $activityData['@default_currency'];
        $activityInfo['@hierarchy'] = $activityData['@hierarchy'];
        $activityInfo['@linked_data_uri'] = $activityData['linked_data_default'];
        $activityInfo['@last_updated_datetime'] = date('Y-m-d H:i:s');
        $activityInfo['activities_id'] = $orgId;
        $activityId = $wepModel->insertRowsToTable('iati_activity', $activityInfo);
        
        $iati_identifier['text'] = trim($iatiIdentifier['iati_identifier']);
        $iati_identifier['activity_identifier'] = trim($iatiIdentifier['activity_identifier']);
        $iati_identifier['activity_id'] = $activityId;
        $wepModel->insertRowsToTable('iati_identifier', $iati_identifier);

        $element = new Iati_Aidstream_Element_Activity();
        $childElements = $element->getChildElements();
        foreach ($childElements as $child) {
            // Don't duplicate IatiIdentifer or Transaction
            if ($child == 'IatiIdentifier' || $child == 'Transaction') continue;
            $child = 'Iati_Aidstream_Element_Activity_' . $child;
            $childElement = new $child;
            $childElement->setDuplicate(true);
            $result = $childElement->fetchData($oldActivityId, true);
            if (count($result)) {
                $elementsData[$childElement->getClassName()] = $result;
                $id = $childElement->save($elementsData[$childElement->getClassName()], $activityId, true);
            }
            unset($elementsData);
        }

        return (int)$activityId;
    }
    
    public function deleteActivityById($activityId) {
        $element = new Iati_Aidstream_Element_Activity();
        $element->deleteElement($activityId);
        
        $modelActivityHash = new Model_ActivityHash();
        $modelActivityHash->deleteActivityHash($activityId);
    }
    
    /**
     *
     * Count activity by status from the given array of activities
     * @param Array $activities array of activities.
     * @return Array    array containing activities count by status.
     */
    public function getCountByState($activities) {
        $state = array();
        foreach ($activities as $activity) {
            $state[$activity['status_id']]++;
        }
        return $state;
    }
    
    public function getLastUpdatedDatetime($activities) {
        $date = $activities[0]['@last_updated_datetime'];
        foreach ($activities as $activity) {
            if ($activity['@last_updated_datetime'] > $date) {
                $date = $activity['@last_updated_datetime'];
            }
        }
        return $date;
    }
    
    public static function updateActivityUpdatedInfo($activityId) {
        $model = new Model_Wep();
        $data['id'] = $activityId;
        $data['@last_updated_datetime'] = date('Y-m-d H:i:s');
        $model->updateRowsToTable('iati_activity', $data);
        
        //change state to editing
        $db = new Model_ActivityStatus;
        $db->updateActivityStatus($activityId, Iati_WEP_ActivityState::STATUS_DRAFT);
    }
    
    public static function getActivityInfo($activityId) {
        $model = new Model_Wep();
        $activityInfo = $model->listAll('iati_activity', 'id', $activityId);
        if (empty($activityInfo)) {
            return false;
        }
        $activity = $activityInfo[0];
        $activity['@xml_lang'] = $model->fetchValueById('Language', $activityInfo[0]['@xml_lang'], 'Code');
        $activity['@default_currency'] = $model->fetchValueById('Currency', $activityInfo[0]['@default_currency'], 'Code');
        
        $iati_identifier_row = $model->getRowById('iati_identifier', 'activity_id', $activityId);
        $activity['iati_identifier'] = $iati_identifier_row['text'];
        $activity['activity_identifier'] = $iati_identifier_row['activity_identifier'];
        $title_row = $model->getRowById('iati_title', 'activity_id', $activityId);
        $activity['iati_title'] = $title_row['text'];
        
        return $activity;
    }
    
    public static function getActivityStatus($activityId) {
        $db = new Model_ActivityStatus();
        return $db->getActivityStatus($activityId);
    }
    
    /**
     * Ported from Admin Controller::listActivityStatesAction()
     * @param None
     * @return Array containing activity states and activity registry published count for all organisations.
     */
    public function allOrganisationsActivityStates() {
        $model = new Model_Wep();
        $activityCollModel = new Model_ActivityCollection();
        
        // $activityModel = new Model_Activity();
        $orgs = $model->listOrganisation('account');
        $orgData = array();
        foreach ($orgs as $organisation) {
            $activities = $activityCollModel->getActivitiesByAccount($organisation['id']);
            $states = $this->getCountByState($activities);
            $organisation['states'] = $states;
            
            $regPublishModel = new Model_RegistryPublishedData();
            $publishedFiles = $regPublishModel->getPublishedInfoByOrg($organisation['id']);
            $publishedActivityCount = $regPublishModel->getActivityCount($publishedFiles);
            $organisation['registry_published_count'] = $publishedActivityCount;
            $orgData[] = $organisation;
        }
        return $orgData;
    }
}