<?php
/**
 * Class to Generate iati xml.
 *
 * @author bhabishyat <bhabishyat@gmail.com>
 */
class Iati_Core_Xml
{
    const SCHEMA_VERSION = 1.01;

    protected $xml;
    protected $xmlPath;
    protected $childrenIds;


    /**
     * Set XmlPath 
     */
    public function __construct()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', APPLICATION_ENV);       
        $this->xmlPath = $config->public_folder.$config->xml_folder;
        
    }
    
    public function setChildrenIds($childrenIds){
        $this->childrenIds= $childrenIds;
    }
    
    public function generateXml($name , $ids = array())
    {
        $this->setChildrenIds($ids);
        
        $this->xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><'.strtolower($name).'s></'.strtolower($name).'s>');
        $this->xml->addAttribute('generated-datetime',gmdate('c'));
        $this->xml->addAttribute('iati-version',self::SCHEMA_VERSION);
        
        if(!empty($this->childrenIds)){
            foreach($this->childrenIds as $childId){
                $childElementClass = "Iati_Aidstream_Element_".ucfirst($name);
                $childElement = new $childElementClass();
                $childElement->getXml($childId , false ,  $this->xml);
            }
        }
        return $this->xml->asXML();
    }
    
    public function generateFile($name , $ids = array())
    {  
        $filename = $this->generateFileName()."-org.xml"; 
        $fp = fopen($this->xmlPath.$filename,'w');
        fwrite($fp,$this->generateXml($name , $ids));
        fclose($fp);
        
        return $filename;
    }
    
    /**
     * Generate the organisation file name 
     * Used user'name from account table to generate file name
     * @return type string
     */
    public function generateFileName()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $account_id = $identity->account_id;
                
        // Get Publisher Name
        $wepModelObj = new Model_Wep();
        $accountInfo = $wepModelObj->getRowsByFields('account', 'id' , $account_id);
        $publisherName = $accountInfo[0]['name'];
        
        return $publisherName;
    }        
}