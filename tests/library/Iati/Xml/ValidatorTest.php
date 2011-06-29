<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Iati_Xml_ValidatorTest extends PHPUnit_Framework_TestCase
{
	protected $xmlFile;
	protected $xmlSchema;

	/**
	 * to set the xml file
	 * @return unknown_type
	 */
	public function setXmlFile($xmlFile) {
		$this->xmlFile = $xmlFile;
	}

	/**
	 * to get the xml file
	 * @return unknown_type
	 */
	public function getXmlFile() {
		return $this->xmlFile;
	}


	/**
	 * to set xml schema
	 * @return unknown_type
	 */
	public function setXmlSchema($xmlSchema) {
		$this->xmlSchema = $xmlSchema;
	}

	/**
	 * to get xml schema
	 * @return unknown_type
	 */
	public function getXmlSchema() {
		return $this->xmlSchema;
	}

	/**
     * Test for the original iati.xsd where xml.xsd namespace has been setup to use local
     * file.
     */
    /*public function testFailValidationDueToMissingXmlXsd()
    {
        $xsdFilename = TEST_PATH.'/schema/iati-1.0.0.xsd';
        $xsdObject = simplexml_load_file($xsdFilename);
        $xsdDom = dom_import_simplexml($xsdObject)->ownerDocument;

        $xmlFilename = TEST_PATH.'/xml/iati-activities-sample.xml';
        $xmlObject = simplexml_load_file($xmlFilename);
        $xmlDom = dom_import_simplexml($xmlObject)->ownerDocument;
        
        $xmlValidator = new Iati_Xml_Validator();
        $result = $xmlValidator->xmlValidate($xmlDom, $xsdDom);
        
        $expectedResult = array(
            'status' => 'fail',
            'error'  => array(
                'DOMDocument::schemaValidateSource(): I/O warning : failed to load external entity "xml.xsd"',
            ),
        );
        $this->assertEquals($expectedResult, $result);
    }
*/
    /**
     * Test for the original iati.xsd where xml.xsd namespace has been setup to use local
     * file.
     *
     * 1.0.2 - has schema in schema/xml.xsd
     */
    public function testPassingValidationWithLocalXmlXsd()
    {
        $xsdFilename = TEST_PATH.'/schema/iati-1.0.2.xsd';
        $xsdObject = simplexml_load_file($xsdFilename);
        $xsdDom = dom_import_simplexml($xsdObject)->ownerDocument;

        $xmlFilename = TEST_PATH.'/xml/UNDP__BFA_iati.xml';
        $xmlObject = simplexml_load_file($xmlFilename);
        $xmlDom = dom_import_simplexml($xmlObject)->ownerDocument;

        $xmlValidator = new Iati_Xml_Validator();
        $result = $xmlValidator->xmlValidate($xmlDom, $xsdDom);

        $expectedResult = array(
            'status' => 'pass',
        );
        $this->assertEquals($expectedResult, $result);
    }

    
    /*public function testBadXmlInput()
    {
        $xsdFilename = TEST_PATH.'/schema/iati-1.0.2.xsd';
        $xsdObject = simplexml_load_file($xsdFilename);
        $xsdDom = dom_import_simplexml($xsdObject)->ownerDocument;

        // uses bad xml file
        $xmlFilename = TEST_PATH.'/xml/iati-activities-sample-bad.xml';
        $xmlObject = simplexml_load_file($xmlFilename);
        $xmlDom = dom_import_simplexml($xmlObject)->ownerDocument;

        $xmlValidator = new Iati_Xml_Validator();
        $result = $xmlValidator->xmlValidate($xmlDom, $xsdDom);

        $expectedResult = array(
            'status' => 'fail',
            'error'  => array(
//                "DOMDocument::schemaValidateSource(): Element 'non-existant-tag': This element is not expected. Expected is ( iati-activity ).",
            ),
        );
        $this->assertEquals($expectedResult, $result);
    }*/




}
