<?php

class Iati_WEP_DbLayerTest extends PHPUnit_Framework_TestCase
{
	private $testObj;

	public function setUp()
	{
		$this->testObj = new Iati_WEP_DbLayer();
	}

	public function testInsertElement(){
		$activities = new Iati_Activity_Element_ActivityCollection();
		$activity = $activities->addElement('activity');
		$activity->setAttribs(array(
            'id' => '11',
		));
		/*     $iatiIdentifier = $activity->addElement('identifier');
		 // fill up properties of $iatiIdentifier

		 // Another technique for adding new element is
		 $reportingOrg = $activity->createElement('reportingOrg');
		 $reportingOrg->setAttribs(array(
		 '@ref' => "GB-1",
		 '@type' => "INGO",
		 '@xml_lang' => "en",
		 'text' => 'TestingText',
		 ));
		 $transaction = $activity->addElement('Transaction');
		 $transaction->setAttribs(array('@ref' => 'reference',
		 ));
		 $transactionType = $transaction->addElement('Transaction_TransactionType');
		 $transactionType->setAttribs(array('@code' => '1',
		 'text' => 'testText',
		 ));
		 $transactionDescription = $transaction->addElement('Transaction_Description');

		 $contactInfo = $activity->addElement('ContactInfo');
		 $contactInfo->setAttribs(array('id' => null,
		 ));

		 $contactInfoEmail = $contactInfo->addElement('ContactInfo_Email');
		 $contactInfoEmail->setAttribs(array('text' => 'testtext',
		 ));

		 $location = $activity->addElement('Location');
		 $location->setAttribs(array('@percentage' => '',
		 ));
		 $locationName = $location->addElement('Location_Name');
		 $locationName->setAttribs(array('@xml_lang' => '1',
		 'text' => 'testText',
		 ));
		 */

		$conditions = $activity->addElement('Conditions');
		$conditions->setAttribs(array(
		    '@attached' => "1",
		));

		$condition = $conditions->addElement('Conditions_Condition');
		$condition->setAttribs(array('@type' => '1',
			     '@xml_lang'=> '133',
			'text' => 'testText',
		));


		//$activity->addElement($conditions);
		$dbLayer = new Iati_WEP_DbLayer();
		$dbLayer->save($activity);
	}

	public function testInsertActivityDateElement(){
		$activities = new Iati_Activity_Element_ActivityCollection();
		$activity = $activities->addElement('activity');
		$activity->setAttribs(array(
            'id' => '27',
		));
		$activityDate = $activity->createElement('activityDate');
		$activityDate->setAttribs(array(
            '@iso_date' => "2010-09-20",
            '@type' => "INGO-2",
            '@xml_lang' => "En",
        	'text' => 'TestingActivityDate',
		));
		$activity->addElement($activityDate);
		$dbLayer = new Iati_WEP_DbLayer();
		$dbLayer->save($activity);
	}


	public function testSaveNullableAttribs()
	{
		$location = new Iati_Activity_Element_Location();
		$location->setAttribs(array('@percentage' => '',
		));
		$locationName = $location->addElement('Location_Name');
		$locationName->setAttribs(array('@xml_lang' => '1',
											'text' => 'testText',
		));
		$dbLayer = new Iati_WEP_DbLayer();
		$dbLayer->save($location);

		$location = new Iati_Activity_Element_Location();
		$location->setAttribs(array('@percentage' => '',
		));

		$dbLayer = new Iati_WEP_DbLayer();
		$dbLayer->save($location);


	}

	public function testUpdateElement(){
		$activities = new Iati_Activity_Element_ActivityCollection();
		$activity = $activities->addElement('activity');
		$activity->setAttrib('@xml_lang', 'en');
		$activity->setAttrib('@default_currency', 'USD');
		$activity->setAttribs(array(
            '@hierarchy' => '0',
            '@last_updated_datetime' => '2011-08-08',
        	'activities_id' => '2',
        	'id' => '8',
		));
		$iatiIdentifier = $activity->addElement('identifier');
		// fill up properties of $iatiIdentifier

		// Another technique for adding new element is
		$reportingOrg = $activity->createElement('reportingOrg');
		$reportingOrg->setAttribs(array(
            '@ref' => "GB-1",
            '@type' => "INGO",
            '@xml_lang' => "en",
        	'text' => 'texting',
        	'id' => '3',
		));
		$activity->addElement($reportingOrg);
		$dbLayer = new Iati_WEP_DbLayer();
		$dbLayer->save($activity);
	}


	public function testSaveElement()
	{

		$initial = array(
            'code' => '34',
            'currency' => '363',
            'xml_lang' => '54',
		);
		$activity = new Iati_WEP_Activity_Elements_Activity();
		$activity->setAttributes(array('activity_id' => '2'));

		$registryTree = Iati_WEP_TreeRegistry::getInstance();
		$registryTree->addNode($activity);

		$transaction = new Iati_WEP_Activity_Elements_Transaction ();
		$transaction->setAttributes($initial);
		$transactionType = new Iati_WEP_Activity_Elements_Transaction_TransactionType ();
		$transactionType->setAttributes($initial);
		$providerOrg = new Iati_WEP_Activity_Elements_Transaction_ProviderOrg ();


		$transaction1 = new Iati_WEP_Activity_Elements_Transaction ();
		$transaction1->setAttributes($initial);
		$transactionType1 = new Iati_WEP_Activity_Elements_Transaction_TransactionType ();
		$transactionType1->setAttributes($initial);
		$providerOrg1 = new Iati_WEP_Activity_Elements_Transaction_ProviderOrg ();


		$registryTree->addNode($transaction, $activity);
		$registryTree->addNode($transactionType, $transaction);
		$registryTree->addNode($providerOrg, $transaction);

		$registryTree->addNode($transaction1, $activity);
		$registryTree->addNode($transactionType1, $transaction1);
		$registryTree->addNode($providerOrg1, $transaction1);

		$classname = 'Iati_Activity_Element_Activity';
		$element = new $classname ();
		$data = $activity->getCleanedData();
		$element->setAttribs($data);
		$factory = new Iati_WEP_Activity_TransactionFactory();
		$activityTree = $factory->cleanData($activity, $element);
		$dbLayer = new Iati_WEP_DbLayer();
		$dbLayer->save($activityTree);
	}

	/*
	 * Test for single row i.e. tree = false
	 */

	public function testFetchRowSet()
	{
		$className = 'Title';
		$fieldName = 'activity_id';
		$value = 2;
		$dbLayer = new Iati_WEP_DbLayer();
		$row = $dbLayer->getRowSet($className,$fieldName,$value,false);
		Zend_Debug::dump($row);
	}

	/*
	 * test for fetching row given parent_id
	 */
	public function testFetchRowTreeParentSet()
	{
		$className = 'Budget';
		$fieldName = 'activity_id';
		$value = 1;
		$tree = true;
		$dbLayer = new Iati_WEP_DbLayer();
		$row = $dbLayer->getRowSet($className,$fieldName,$value,$tree);
		Zend_Debug::dump($row);
	}

	public function testFetchRowTreeSetResult()
	{
		$className = 'Result';
		$fieldName = 'activity_id';
		$value = 1;
		$tree = true;
		$dbLayer = new Iati_WEP_DbLayer();
		$row = $dbLayer->getRowSet($className,$fieldName,$value,$tree);
		Zend_Debug::dump($row);
	}

	public function testFetchRowTreeSetValidAttribs()
	{
		$className = 'Activity';
		$fieldName = 'activities_id';
		$value = 1;
		$tree = true;
		$dbLayer = new Iati_WEP_DbLayer();
		$row = $dbLayer->getRowSet($className,$fieldName,$value,$tree,true);					
		Zend_Debug::dump($row);
	}

	/*
	 *test for fetching row given own_id (primaryKey)
	 *
	 */

	public function testFetchRowOwnTreeSet()
	{
		$className = 'ActivitiesCollection';
		$fieldName = 'id';
		$value = 2;
		$tree = true;
		$dbLayer = new Iati_WEP_DbLayer();
		$row = $dbLayer->getRowSet($className,$fieldName,$value,$tree);
		Zend_Debug::dump($row);


	}

	public function testDeleteRowOnOwnId()
	{
		$className = 'Activity';
		$fieldName = 'id';
		$value = 1;
		$dbLayer = new Iati_WEP_DbLayer();
		$del = $dbLayer->deleteRows($className, $fieldName, $value);

	}

	public function testDeleteRowOnParentId()
	{
		$className = 'Transaction';
		$fieldName = 'activity_id';
		$value = 1;
		$dbLayer = new Iati_WEP_DbLayer();
		$del = $dbLayer->deleteRows($className, $fieldName, $value);

	}

	public function testConditionFormatter()
	{
		$className = 'Transaction';
		$result = $this->testObj->conditionFormatter($className);
		$this->assertEquals('transaction_id', $result);

		$className = 'PlannedDisbursement';
		$result = $this->testObj->conditionFormatter($className);
		$this->assertEquals('planned_disbursement_id', $result);

		$className = 'Result_Indicator';
		$result = $this->testObj->conditionFormatter($className);
		$this->assertEquals('indicator_id', $result);


	}

	public function testAttribs()
	{
		$dbLayer = new Iati_WEP_DbLayer();

		$attribs = array(
            '@ref' => "GB-1",
            '@type' => "INGO",
            '@xml_lang' => "en",
        	'text' => 'TestingText',
		);
		$attrib = $dbLayer->checkIsEmptyAttribs($attribs);
		$this->assertTrue($attrib);

		$attribs = array(
            'id' => "0",
            '@type' => "",
            '@xml_lang' => "",
        	'text' => '',
		);
		$attrib = $dbLayer->checkIsEmptyAttribs($attribs);
		$this->assertFalse($attrib);

		$attribs = array(
            'id' => null,
		);
		$attrib = $dbLayer->checkIsEmptyAttribs($attribs);
		$this->assertTrue($attrib);

	}

	public function testactivityTreeMapper()
	{
		$activitMapper = new Iati_WEP_ActivityTreeMapper();

		$className = 'Activity';
		$result = $activitMapper->getActivityTree($className);
		$expectedResult = array(
		0 => 'Transaction',
		1 => 'Conditions',
		2 => 'DocumentLink',
		3 => 'Result',
		4 => 'PlannedDisbursement',
		5 => 'Identifier',
		6 => 'ContactInfo',
		7 => 'ReportingOrg',
		8 => 'Identifier',
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
		);
		$this->assertEquals($expectedResult, $result);

		$className = 'Transaction';
		$result = $activitMapper->getActivityTree($className);
		$expectedResult = array(
		0 => 'Transaction_TransactionDate',
		1 => 'Transaction_TransactionType',
		2 => 'Transaction_AidType',
		3 => 'Transaction_Description',
		4 => 'Transaction_FinanceType',
		5 => 'Transaction_ProviderOrg',
		6 => 'Transaction_ReceiverOrg',
		7 => 'Transaction_Value',
		8 => 'Transaction_TiedStatus',
		9 => 'Transaction_DisbursementChannel',
		10 => 'Transaction_FlowType',
		);
		$this->assertEquals($expectedResult, $result);

	}


}