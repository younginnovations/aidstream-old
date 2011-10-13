<?php

class Iati_Registry
{
    protected $publisher_org_id;
    protected $publisher_org_name;
    protected $publisher_region;
    protected $file_path;
    protected $segmented;
    
    public function __construct($publisher_org_id,$publisher_org_name,$segmented = false)
    {
        $this->publisher_org_id = $publisher_org_id;
        $this->publisher_org_name = $publisher_org_name;
        $this->segmented = $segmented;
        
        $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', APPLICATION_ENV);
        $this->file_path = $config->public_folder.$config->xml_folder;
        
    }
    public function publish()
    {
        $oActivitycollection = new Iati_ActivityCollection();
        $activities = $oActivitycollection->getPublishedActivityCollection($this->publisher_org_id);
        if($this->segmented) {
            //Seperate activities by country or region
            foreach($activities as $activity)
            {
                $country = $activity->getElementsByType(Iati_Activity_Element::TYPE_RECIPIENT_COUNTRY);
                $country_attribs = $country[0]->getAttribs();
                if(empty($country_attribs)){
                    $region = $activity->getElementsByType(Iati_Activity_Element::TYPE_RECIPIENT_REGION);
                    $region_attribs = $region[0]->getAttribs();
                    if(!empty($region_attribs)){
                        $segmented_activities[$region[0]->getAttribValue('@code','Name')][] = $activity;
                    } else {
                        //print('Country name or region name not found');
                    }
                } else {
                    $segmented_activities[$country[0]->getAttribValue('@code','Name')][] = $activity;
                }
            }
            
            //print each segments activities xml
            foreach ($segmented_activities as $org=>$activities)
            {
                $this->publisher_region = $org;
                $filename = $this->saveActivityXml($activities);
                $this->savePublishInfo($filename);                
            }
            
        } else {
            $filename = $this->saveActivityXml($activities);
            $this->savePublishInfo($filename);
        }
    }
    
    public function saveActivityXml($activities)
    {
        $oXmlHandler = new Iati_WEP_XmlHandler($activities);
        $file = strtolower($this->publisher_org_name);
        if($this->segmented)
        {
            $file = $file."_".strtolower($this->publisher_region);
        }
        $file = preg_replace('/ /','_',$file);
        $file = preg_replace('/,/','',$file);
        $filename = $file.".xml";
        
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