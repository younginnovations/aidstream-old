<?php

class Iati_WEP_TableClassMapper
{

    protected $classMapper = array(
        'Title' => 'iati_title',
        'ActivityDate' => 'iati_activity_date',
        'ReportingOrganisation' => 'iati_reporting_org',
        'IatiIdentifier' => 'iati_identifier',
        'Transaction' => 'iati_transaction',
        'Transaction_TransactionType' => 'iati_transaction/transaction_type',
        'Transaction_ProviderOrg' => 'iati_transaction/provider_org',
        'Transaction_AidType' => 'iati_transaction/aid_type',
        'Transaction_TransactionDate' => 'iati_transaction/transaction_date',
        'Transaction_Description' => 'iati_transaction/description',
        'Transaction_ReceiverOrg' => 'iati_transaction/receiver_org',
        'Transaction_Value' => 'iati_transaction/value',
        'Activity' => 'iati_activity',
        'ReportingOrg' => 'iati_reporting_org',
        'Conditions' => 'iati_conditions',
        'Conditions_Condition' => 'iati_conditions/condition',
        'DocumentLink' => 'iati_document_link',
        'Result' => 'iati_result',
        'PlannedDisbursment' => 'iati_planned_disbursment'
    );

    public function getTableName($classname)
    {
        $strippedClassName = str_replace('Iati_WEP_Activity_Elements_', "", $classname);
        $classNames = explode("_",$strippedClassName,3);
		foreach($classNames as $eachClassName){
			$result = $this->convertCamelCaseToDash($eachClassName);
			if($tableName)
			$tableName = $tableName."/".$result;
			else
			$tableName = $result;
		}
		return "iati_".$tableName;

    }

	public function convertCamelCaseToDash($className) {
		$conditionField = strtolower(preg_replace('/([^A-Z_])([A-Z])/', '$1_$2', $className));
		return $conditionField;
	}

}