<?php

class Iati_WEP_ActivityTreeMapper {

	private $elementTree = array(
	'Transaction' =>
        	array(
        	0 => 'Transaction_TransactionType',
        	1 => 'Transaction_ProviderOrg',
        	2 => 'Transaction_ReceiverOrg',
        	3 => 'Transaction_Value',
        	4 => 'Transaction_Description',
        	5 => 'Transaction_TransactionDate',
        	6 => 'Transaction_FlowType',
        	7 => 'Transaction_FinanceType',
        	8 => 'Transaction_AidType',
        	9 => 'Transaction_DisbursementChannel',
        	10 => 'Transaction_TiedStatus',
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
    'Result_Indicator' =>
        	array(
        	0 => 'Result_Indicator_Actual',
        	1 => 'Result_Indicator_Baseline',
        	2 => 'Result_Indicator_Description',
        	3 => 'Result_Indicator_Target',
        	4 => 'Result_Indicator_Title',
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
	'Location' =>
        	array(
        	0 => 'Location_LocationType',
        	1 => 'Location_Name',
        	2 => 'Location_Description',
        	3 => 'Location_Administrative',
        	4 => 'Location_Coordinates',
        	5 => 'Location_GazetteerEntry'
        	),
    'Budget' =>
        	array(
        	0 => 'Budget_PeriodEnd',
        	1 => 'Budget_PeriodStart',
        	2 => 'Budget_Value'
        	),
      'Activity' =>
			array(
			0 => 'Transaction',
			1 => 'Conditions',
			2 => 'DocumentLink',
			3 => 'Result',
			4 => 'PlannedDisbursement',
			5 => 'Identifier',
			6 => 'ContactInfo',
			7 => 'ReportingOrg',
			//8 => 'Identifier',
			9 => 'OtherActivityIdentifier',
			10 => 'Title',
			11 => 'Description',
			12 => 'ActivityStatus',
			13 => 'ActivityDate',
			14 => 'ParticipatingOrg',
			15 => 'RecipientCountry',
			16 => 'RecipientRegion',
			17 => 'Location',
			18 => 'Sector',
			19 => 'PolicyMarker',
			20 => 'CollaborationType',
			21 => 'DefaultFlowType',
			22 => 'DefaultFinanceType',
			23 => 'DefaultAidType',
			24 => 'DefaultTiedStatus',
			25 => 'Budget',
			25 => 'ActivityWebsite',
			26 => 'RelatedActivity',
			),
	);


	/**
	 * @param string $className
	 * @return array of childElements
	 */
	public function getActivityTree($className) {
		$result = $this->elementTree[$className];
		return $result;
	}
}

