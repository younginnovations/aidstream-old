<?php
require_once('Iati/Iatischema/Validator.php');
class Iati_Iatischema_ValidatorTest extends PHPUnit_Framework_TestCase
{
	public function testIatiSchema() 
	{
		$xmlDOMDocument = new DomDocument();
		$xmlDOMDocument->load('xml/UNDP__BFA_iati.xml');
		$xmlValidator = new Iati_Iatischema_Validator();
		$result = $xmlValidator->validate($xmlDOMDocument);
		var_dump($result);
		
		/*libxml_use_internal_errors(true);
		  foreach(libxml_get_errors() as $error) {
			//print br();
			$error_msg[] = sprintf('XML error "%s" [%d] (Code %d) in %s on line %d column %d' . "\n",
			$error->message, $error->level, $error->code, $error->file,
			$error->line, $error->column);
			
			$result['status'] = 'fail';
			$result['error'] = $error_msg;libxml_clear_errors();
		}*/
		
		$this->assertEquals('pass', $result['status']);
	}

    public function testGetAvailableSchemaVersions()
    {
        $validator = new Iati_Iatischema_Validator();
        $versions = $validator->getAvailableSchemaVersions();

//        var_dump($versions);
        $expected = array(
            '1_01',
        );

        $this->assertEquals($expected, $versions);
    }
}