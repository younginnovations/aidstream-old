<?php

class Iati_Registry
{
    protected $org_id;
    protected $publisher_name;
    
    public function __construct($publisher_org_id,$publisher_org_name)
    {
        $this->publisher_org_id = $publisher_org_id;
        $this->publisher_org_name = $publisher_org_name;
        
        $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', APPLICATION_ENV);
        $this->file_path = $config->registry_xmlfile_path;
        
    }
    public function publish()
    {
        $oActivitycollection = new Iati_ActivityCollection();
        $activities = $oActivitycollection->getPublishedActivityCollection($this->publisher_org_id);
        
        $filename = $this->saveActivityXml($activities);
        $this->savePublishInfo($filename);
    }
    
    public function saveActivityXml($activities)
    {
        $oXmlHandler = new Iati_WEP_XmlHandler($activities);
        
        $filename = strtolower($this->publisher_org_name).".xml";
        $fp = fopen($this->file_path.$filename,'w');
        fwrite($fp,$oXmlHandler->getXmlOutput());
        
        return $filename;
    }
    public function savePublishInfo($filename)
    {
        $db = new Model_Registry();
        $data = array(
                    'publishing_org_id' => $this->publisher_org_id,
                    'filename' => $filename,
                    'published_date' => date('Y-m-d h:m:s')
                    );
        $db->saveRegistryInfo($data);
    }
    
    public function getPublishedList()
    {
        
    }
    
    public function updateRegistry()
    {
        
    }
    
}