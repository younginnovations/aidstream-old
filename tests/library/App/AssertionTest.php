<?php

class App_AssertionTest extends PHPUnit_Framework_TestCase
{
	private $testObj;

	public function setup()
	{
		$this->testObj = new App_AssertionCheck();
	}

	public function testResourceCheckView()
	{
		$userId = 26;
		$resource = 'view';
		$result = $this->testObj->resourceCheck($userId, $resource);
		$this->assertTrue($result);
	}

	public function testResourceCheckEdit()
	{
		$userId = 26;
		$resource = 'edit';
		$result = $this->testObj->resourceCheck($userId, $resource);
		$this->assertTrue($result);
	}
}