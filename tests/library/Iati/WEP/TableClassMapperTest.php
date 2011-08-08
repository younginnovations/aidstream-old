<?php
class Iati_WEP_TableClassMapperTest extends PHPUnit_Framework_TestCase
{
	private $testObj;

	public function setUp()
	{
		$this->testObj = new Iati_WEP_TableClassMapper();
	}

	public function testGetTableName()
	{
		$className = 'TransactionType';
		$parentName = "Transaction";
		$tableName = $this->testObj->getTableName($className,$parentName);
		$this->assertEquals('iati_transaction/transaction_type', $tableName);

		$className = 'PlannedDisbursement_PeriodStart';
		$tableName = $this->testObj->getTableName($className);
		$this->assertEquals('iati_planned_disbursement/period_start', $tableName);

		$className = 'Result';
		$tableName = $this->testObj->getTableName($className);
		$this->assertEquals('iati_result', $tableName);

		$className = 'Result_Indicator_Description';
		$tableName = $this->testObj->getTableName($className);
		$this->assertEquals('iati_result/indicator/description', $tableName);

		$className = 'ActivityDate';
		$tableName = $this->testObj->getTableName($className);
		$this->assertEquals('iati_activity_date', $tableName);
	}
}