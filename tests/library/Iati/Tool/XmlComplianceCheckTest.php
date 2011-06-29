<?php
class Iati_Tool_XmlComplianceCheckTest extends PHPUnit_Framework_TestCase
{
    
	public $config;
    public function setup()
    {
        $this->config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini',
                                    'testing');
        parent::setUp();
    }
	public function testXmlParser()
    {
        $complianceCheck = new Iati_Tool_XmlComplianceCheck();
        $complianceCheck->setXml("http://localhost/UK");
        $parsedXml = $complianceCheck->xmlParser();
        $actual = '';
//        print_r($parsedXml);
        $expectedResult = '';
        $this->assertEquals($expectedResult, $actual);
    }
    
    public function testPrepareHandle()
    {
        $complianceCheck = new Iati_Tool_XmlComplianceCheck();
        $complianceCheck->setFolderPath($this->config->compliance->folderpath);
        $complianceCheck->setDestination($this->config->compliance->destination);
        $complianceCheck->setXml("http://localhost/AS");
        $complianceCheck->xmlParser();
       
        $path = $complianceCheck->validate();
        $f = $complianceCheck->getFileName();
        print $f;print "\n";
        print $path;
        $actual = '';
        $expectedResult = '';
        $this->assertEquals($expectedResult, $actual);
    }
    
    public function testSetXmlArray()
    {   
        $array = array(
                    array(
                        'iati-activity' => array(
                                                'default-currency'=>'GPB',
                                                'hierarchy'=>'1',
                                            ),      
                        'reporting-org' => array(
                                                'ref' => array('GB-1'),
                                                'type' => array('10'),
                                                'text' => array('Department of International Development'),
                                            ),
                        'participating-org' => array(
                                                'ref' => array('GB', 'GB-1'),
                                                'type' => array('10', '10'),
                                                'role' => array('Funding', 'Extending'),
                                                'text' => array('UNITED KINGDOM', 'Department of International'),
                                            ),
                        'contact-info' => array(
                                                'organisation' => 'Department for International Development',
                                                'telephone' => '+44 (0) 1355 84 3132',
                                                'email' => 'enquiry@dfid.gov.uk',
                                                'mailing-address' => 'Public Enquiry Point, Abercrombie House, Eaglesham Road, East Kilbride, Glasgow G75 8EA',
                                        ),
                          ),  
                    array(
                        'iati-activity' => array(
                                                'default-currency' => 'GPB',
                                            ),
                        'transaction' => array(
                                                'value' => array(
                                                                'value-date' => '2009-04-01Z',
                                                                'text' => '10000',
                                                            ),
                                                'transaction-type' => Array(
                                                                        'code' => 'C',
                                                                        'text' => 'Commitment',
                                                                        ),

                                                'transaction-date' => Array(
                                                        'iso-date' => '2009-04-01Z',
                                                        'text' => 'Budget for financial year 2009',
                                                    ),    
                                            ),
                    ),  
                 );
                 return $array;
    }
    
    public function testCsvReader()
    {
        $complianceCheck = new Iati_Tool_XmlComplianceCheck();
        $complianceCheck->setFolderPath("/home/manisha/Documents/iati_docs/standard/");
//        $complianceCheck->setCsv('currency');
        $result = $complianceCheck->csvReader('currency',1);
        print_r($result);
        $actual = '';
        $expected = '';
        $this->assertEquals($expected, $actual);
    }
    
    public function testValidate()
    {
        $array = array(array(
                        'iati-activity' => Array
                (
                    '0' => Array
                        (
                            'default-currency' => 'GB',
                            'xml:lang' => '10',
                        ),

                    '1' => Array
                        (
                            'default-currency' => 'GB',
                        )

                )
        
                    )
                    );
        $complianceCheck = new Iati_Tool_XmlComplianceCheck();
        $complianceCheck->setXmlArray($array);
        $matrix = new Iati_Tool_MatrixGenerator();
        $complianceCheck->setFolderPath($this->config->compliance->folderpath);
   
        $complianceCheck->validate();
    }
    
    public function testMatrixGenerator()
    {
         $object = new Iati_Tool_IatiActivity();
         $array = array($object, $object);
        
        $complianceCheck = new Iati_Tool_XmlComplianceCheck();
        $complianceCheck->setFolderPath($this->config->compliance->folderpath);
        $complianceCheck->setDestination($this->config->compliance->destination);
//        $complianceCheck->setXml("http://localhost/AS");
//        $complianceCheck->xmlParser();
//        $complianceCheck->prepareHandle();
        $complianceCheck->setFilename('AS');
        $complianceCheck->matrixGenerator($array);
        $actual = '';
        $expectedResult = '';
        $this->assertEquals($expectedResult, $actual);
    }
    
    public function testReadCsv(){
        
        $complianceCheck = new Iati_Tool_XmlComplianceCheck();
//        $complianceCheck->setCsv("http://localhost/testing.xml");
        $complianceCheck->readCsv();
    }
    
}