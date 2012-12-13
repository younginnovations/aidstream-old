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
    
    public function setXmlPath($path){
        $this->xmlPath = $path;    
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

        echo $this->xml->asXML();exit;
    }
}