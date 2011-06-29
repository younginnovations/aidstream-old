<?php

class Iati_Xml_Validator
{
    protected $xmlFile;
    protected $xmlSchema;

    public function __construct()
    {
        libxml_use_internal_errors(true);
    }
    
    /**
     * to set the xml file
     * @return unknown_type
     */
    public function setXmlFile($xmlFile)
    {
        $this->xmlFile = $xmlFile;
    }

    /**
     * to get the xml file
     * @return unknown_type
     */
    public function getXmlFile()
    {
        return $this->xmlFile;
    }

    /**
     * to set xml schema
     * @return unknown_type
     */
    public function setXmlSchema($xmlSchema)
    {
        $this->xmlSchema = $xmlSchema;
    }

    /**
     * to get xml schema
     * @return unknown_type
     */
    public function getXmlSchema()
    {
        return $this->xmlSchema;
    }

    /**
     * function takes two parameters
     * 
     * $this->xmlFile is the uploaded xml file
     * $this->xmlSchema is the xsd file, and xml file is validate against xsd file
     * return the sucess if the xml file is valid ortherwise return the error message
     * @return unknown_type
     */
    public function xmlValidate(DOMDocument $filedom, DOMDocument $schemadom)
    {
        $result['status'] = 'fail';
        try {
            $val = $filedom->schemaValidateSource($schemadom->saveXml());
            if (!$val) {
                foreach (libxml_get_errors() as $error) {
//                    $result['error'][] = sprintf('XML error "%s" [%d] (Code %d) in %s on line %d column %d' . "\n",
//        			$error->message, $error->level, $error->code, $error->file,
//        			$error->line, $error->column);
                    $result['error'][] = $error->message;
                }
            }
        } catch (Exception $e) {
        	$errormsg[] = $e->getMessage(); 
            $result['error'] = $errormsg;
        }

        if (empty($result['error'])) {
            $result['status'] = 'pass';
        }
        
        return $result;
    }

    public function xmlValidateBySchemaFilepath(DOMDocument $filedom, $schemaFilepath)
    {
        $result['status'] = 'fail';
        try {
            $val = $filedom->schemaValidate($schemaFilepath);
            if (!$val) {
                foreach (libxml_get_errors() as $error) {
                    $result['error'][] = $this->formatXmlError($error);;
                }
            }
        } catch (Exception $e) {

            $result['status'] = 'fail';
            $errormsg[] = $e->getMessage();
            $result['error'] = $errormsg;
        }
        
        if (empty($result['error'])) {
            $result['status'] = 'pass';
        }

        return $result;
    }

    public function formatXmlError(LibXMLError $error)
    {
        return "XML error \"$error->message\" [$error->level] (Code $error->code) in $error->file on line $error->line" .
    " column $error->column";
    }
}