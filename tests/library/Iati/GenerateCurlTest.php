<?php 

//require_once('');
class Iati_GenerateCurlTest extends PHPUnit_Framework_TestCase
{
	
	public function testSetCurl()
	{
		$url = "http://localhost/webservice-iatixml/public/test/testurl";
		$data = array('data' => 'Pass');
		
		$curlGenerator = new Iati_Generatecurl();
		$result = $curlGenerator->setcurl($url, $data);
		$expectedResult = 'Pass';
		$this->assertEquals($expectedResult, $result);
//		$this->assertTrue($result);
		
	}
}
?>    