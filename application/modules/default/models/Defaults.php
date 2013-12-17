<?php
class Model_Defaults
{
    function updateDefaults($data , $accountId = '')
    {
        if(!$accountId){
            $identity = Zend_Auth::getInstance()->getIdentity();
            $accountId = $identity->account_id;
        }
        
        $model = new Model_Wep();        

        //Update default values
        $dfg = new Model_DefaultFieldValues();
        //User existing default values setting.
        $defaultObj = $dfg->getDefaultObjByOrganisation($accountId);
        $fieldString = $this->prepareDefaultFieldValues($data , $defaultObj);
        $defaultValues['id'] = $model->getIdByField(
                                                'default_field_values',
                                                'account_id',
                                                $accountId);
        $defaultValues['object'] = $fieldString;
        $defaultValuesId = $model->updateRowsToTable(
                                                    'default_field_values',
                                                    $defaultValues
                                                );
        //Update Default Fields
        if(!empty($data['default_fields'])){
            $defaultFieldGroupObj = new Iati_WEP_AccountDisplayFieldGroup();
            foreach ($data['default_fields'] as $eachField) {
                print $eachField;
                $defaultFieldGroupObj->setProperties($eachField);
            }
    
            $fieldString = serialize($defaultFieldGroupObj);
            $defaultFields['id'] = $model->getIdByField(
                                                        'default_field_groups',
                                                        'account_id',
                                                        $accountId
                                                    );
            $defaultFields['object'] = $fieldString;
            $defaultFieldId = $model->updateRowsToTable('default_field_groups', $defaultFields);
        }
    }
    
    public function createDefaults($data , $accountId)
    {
        $model = new Model_Wep();        

        //Save default values
        $fieldString = $this->prepareDefaultFieldValues($data);
        $defaultValues['object'] = $fieldString;
        $defaultValues['account_id'] = $accountId;
        $defaultValuesId = $model->insertRowsToTable('default_field_values', $defaultValues);
        
         //Insert Default Fields
        $defaultFieldGroup = new Iati_WEP_AccountDisplayFieldGroup();
        $default = array('title','description','activity_status','activity_date',
                         'participating_org','recipient_country','sector',
                         'budget','transaction');

        foreach ($default as $eachField) {
            $defaultFieldGroup->setProperties($eachField);
        }

        $fieldString = serialize($defaultFieldGroup);
        $defaultFields['object'] = $fieldString;
        $defaultFields['account_id'] = $accountId;
        $defaultFieldId = $model->insertRowsToTable('default_field_groups', $defaultFields);
    }
    
    public function prepareDefaultFieldValues($data , $defaultObj = '')
    {
        if(!$defaultObj){
            $defaultObj = new Iati_WEP_AccountDefaultFieldValues();
        }

        //set provided data.
        if ($data['default_language']){
            $defaultObj->setLanguage($data['default_language']);
        }
        
        if ($data['default_currency']){
            $defaultObj->setCurrency($data['default_currency']);
        }
        
        if ($data['default_reporting_org']){
            $defaultObj->setReportingOrg($data['default_reporting_org']);
        }
        
        if ($data['hierarchy']){
            $defaultObj->setHierarchy($data['hierarchy']);
        }
        
        if ($data['linked_data_default']){
            $defaultObj->setLinkedDataDefault($data['linked_data_default']);
        }
        
        if ($data['reporting_org_ref']){
            $defaultObj->setReportingOrgRef($data['reporting_org_ref']);
        }
        
        if ($data['reporting_org_type']){
            $defaultObj->setReportingOrgType($data['reporting_org_type']);
        }
        
        if ($data['reporting_org_lang']){
            $defaultObj->setReportingOrgLang($data['reporting_org_lang']);
        }
        
        if ($data['default_collaboration_type']){
            $defaultObj->setCollaborationType($data['default_collaboration_type']);
        }
        
        if ($data['default_flow_type']){
            $defaultObj->setFlowType($data['default_flow_type']);
        }
        
        if ($data['default_finance_type']){
            $defaultObj->setFinanceType($data['default_finance_type']);
        }
        
        if ($data['default_aid_type']){
            $defaultObj->setAidType($data['default_aid_type']);
        }
        
        if ($data['default_tied_status']){
            $defaultObj->setTiedStatus($data['default_tied_status']);
        }

        $fieldString = serialize($defaultObj);
        return $fieldString;
    }
}