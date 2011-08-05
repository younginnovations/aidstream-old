<?php

class Iati_WEP_DbLayerTest extends PHPUnit_Framework_TestCase
{
	private $testObj;

	public function setUp()
	{
		$this->testObj = new Iati_WEP_DbLayer();
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

	public function testFetchRowSet()
	{
		$className = 'Title';
		$fieldName = 'activity_id';
		$value = 2;
		$dbLayer = new Iati_WEP_DbLayer();
		$row = $dbLayer->getRowSet($className,$fieldName,$value,false);
		Zend_Debug::dump($row);


	}

	public function testFetchRowTreeSet()
	{
		$className = 'Transaction';
		$fieldName = 'activity_id';
		$value = 2;
		$tree = true;
		$dbLayer = new Iati_WEP_DbLayer();
		$row = $dbLayer->getRowSet($className,$fieldName,$value,$tree);
		Zend_Debug::dump($row);


	}

	public function testFetchRowOwnTreeSet()
	{
		$className = 'Activity';
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

	}


}