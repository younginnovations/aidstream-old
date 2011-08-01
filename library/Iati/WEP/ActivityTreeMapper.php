<?php

class Iati_WEP_ActivityTreeMapper {

	private $elementTree = array('Transaction' =>
	array(
	0 => 'Transaction_Date',
	1 => 'Transaction_TransactionType',
	2 => 'Transaction_AidType',
	3 => 'Transaction_Description',
	4 => 'Transaction_FinanceType',
	5 => 'Transaction_ProviderOrg',
	6 => 'Transaction_ReceiverOrg',
	7 => 'Transaction_Value',
	8 => 'Transaction_TiedStatus',
	),
        'Conditions' =>
	array(
	0 => 'Conditions_Condition',
	),
        'DocumentLink' =>
	array(
	0 => 'DocumentLink_Category',
	1 => 'DocumentLink_Title',
	),
        'Result' =>
	array(
	0 => 'Result_Description',
	1 => 'Result_Indicator',
	2 => 'Result_Title',
	),
        'PlannedDisbursement' =>
	array(
	0 => 'PlannedDisbursement_PeriodEnd',
	1 => 'PlannedDisbursement_PeriodStart',
	2 => 'PlannedDisbursement_Value',
	),
       'ContactInfo' =>
	array(
	0 => 'ContactInfo_Organisation',
	1 => 'ContactInfo_PersonName',
	2 => 'ContactInfo_Telephone',
	3 => 'ContactInfo_Email',
	4 => 'ContactInfo_MailingAddress',
	),
	);
	private $activityTree = array('Activity' =>
	array(0 => 'Transaction',
	1 => 'Conditions',
	2 => 'DocumentLink',
	3 => 'Result',
	4 => 'PlannedDisbursement',
	5 => 'IatiIdentifier',
	6 => 'ContactInfo',
	),);

	public function getActivityTree($className, $parent = null) {
		if ($className == 'Activity') {
			$result = $this->activityTree['Activity'];
		} else {
			$result = $this->elementTree[$className];
		}
		return $result;
	}
}

