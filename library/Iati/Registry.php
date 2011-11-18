<?php

class Iati_Registry
{
    protected $publisher_org_id;
    protected $publisher_id;
    protected $publisher_region;
    protected $file_path;
    protected $segmented;
    
    public function __construct($publisher_org_id,$publisher_id,$segmented = false)
    {
        $this->publisher_org_id = $publisher_org_id;
        $this->publisher_id = $publisher_id;
        $this->segmented = $segmented;
        
        $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', APPLICATION_ENV);
        $this->file_path = $config->public_folder.$config->xml_folder;
        
    }
    public function publish()
    {
        $oActivitycollection = new Iati_ActivityCollection();
        $activities = $oActivitycollection->getPublishedActivityCollection($this->publisher_org_id);
        if($this->segmented) {
            /**
             *Seperate activities by country or region
             *
             *foreach activity
             *if count of countries of an activity greater than 1: filename should use 
             *if count of countries 1 for no country or only one country
             *  if size of attrib of country 0 i.e no country, check simillarly for region
             *  else filename should use country name
            **/
            foreach($activities as $activity)
            {
                $country = $activity->getElementsByType(Iati_Activity_Element::TYPE_RECIPIENT_COUNTRY);
                if(sizeof($country) > 1){
                    $segmented_activities[998][] = $activity;
                } else {
                    $countryAttribs = $country[0]->getAttribs();
                    if(0 == sizeof($countryAttribs)){
                        $region = $activity->getElementsByType(Iati_Activity_Element::TYPE_RECIPIENT_REGION);
                        if(1 > sizeof($region)){
                            $segmented_activities[998][] = $activity;
                        } else {
                            $regionAttribs = $region[0]->getAttribs();
                            if(0 == sizeof($regionAttribs)){
                                $segmented_activities[998][] = $activity;
                            } else {
                                $segmented_activities[$region[0]->getAttribValue('@code')][] = $activity;
                            }
                        }
                        $segmented_activities[998][] = $activity;
                    } else {
                        $segmented_activities[$country[0]->getAttribValue('@code')][] = $activity;
                    }
                    
                }
            }
            
            // reset existing published info
            $this->resetPublishedInfo();
            
            //print each segments activities xml and save published info
            foreach ($segmented_activities as $org=>$activities)
            {
                $this->publisher_region = $org;
                $filename = $this->saveActivityXml($activities);
                $this->savePublishedInfo($filename);                
            }
            
        } else {
            // remove existing published info
            $this->resetPublishedInfo();
            
            $filename = $this->saveActivityXml($activities);
            $this->savePublishedInfo($filename);
        }
    }
    
    public function saveActivityXml($activities)
    {
        $oXmlHandler = new Iati_WEP_XmlHandler($activities);
        $file = strtolower($this->publisher_id);
        if($this->segmented){
            $file .= "-".strtolower($this->publisher_region);
        } else {
            $file .= "-activities";
        }
        $file = preg_replace('/ /','_',$file);
        $file = preg_replace('/,/','',$file);
        $filename = $file.".xml";
        
        $fp = fopen($this->file_path.$filename,'w');
        fwrite($fp,$oXmlHandler->getXmlOutput());
        
        return $filename;
    }
    public function savePublishedInfo($filename)
    {
        $db = new Model_Published();
        $data = array(
                    'publishing_org_id' => $this->publisher_org_id,
                    'filename' => $filename,
                    'published_date' => date('Y-m-d h:m:s')
                    );
        $db->savePublishedInfo($data);
    }
    
    public function resetPublishedInfo()
    {
        $modelPublished = new Model_Published();
        $modelPublished->resetPublishedInfo($this->publisher_org_id);
    }
    
    public function getPublishedList()
    {
        
    }
    
    public function updateRegistry()
    {
        
    }
    
}