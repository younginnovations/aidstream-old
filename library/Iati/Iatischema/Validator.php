<?php

define('IATI_IATISCHEMA_SCHEMDIR', dirname(__FILE__) . '/schema');
/**
 * 
 * @author yipl
 *
 */
class Iati_Iatischema_Validator
{
    static protected $_schemaPath = IATI_IATISCHEMA_SCHEMDIR;

    static public function setSchemaPath($schemaPath)
    {
        self::$_schemaPath = $schemaPath;
    }
    
    /**
     *
     * @param DOMDocument $xmlDOMDocument
     * @param string $schemaName the name of the schema
     * @param string $schemaVersion schema version, default is 1_01
     * @return array
     * - status string, either pass or fail
     * - error array each element is the error
     */
    public function validate(DOMDocument $xmlDOMDocument, $schemaName = 'iati-activities-schema', $schemaVersion = '1_01')
	{
        $availableSchemas = $this->getAvailableSchemaVersions();
        if (!in_array($schemaVersion, $availableSchemas)) {
            $result = array(
                'status' => 'fail',
                'error'  => array('Invalid Schema Version'),
            );

            return $result;
        }
        
        $schema = self::$_schemaPath . DIRECTORY_SEPARATOR . $schemaVersion . DIRECTORY_SEPARATOR .
            $schemaName . '.xsd';

        $xmlValidator = new Iati_Xml_Validator();
        $result = $xmlValidator->xmlValidateBySchemaFilepath($xmlDOMDocument, $schema);
		
		return $result;
	}

    /**
     * Returns schema versions available
     */
	public function getAvailableSchemaVersions()
    {
//        var_dump(IATI_IATISCHEMA_SCHEMDIR);exit();
        
        $schemaPath = self::$_schemaPath;
        if (!is_dir($schemaPath)) {
            throw new Exception('Invalid Schema Path');
        }

        $schemaDirs = array();
        $dir = dir($schemaPath);
        while (false !== ($entry = $dir->read())) {
            if ($entry == '..' || $entry == '.') {
                continue;
            }
            $pathName = $schemaPath . '/' . $entry;

            if (is_dir($pathName)) {
                $schemaDirs[] = $entry;
            }   
        }

        return $schemaDirs;
    }

    static public function getSchemaPath()
    {
        return self::$_schemaPath;
    }
}