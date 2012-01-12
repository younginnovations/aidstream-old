<?php

class Iati_WEP_Publish
{
    protected $publisher_org_id;
    protected $publisher_id;
    protected $api_key;
    protected $recipient;
    protected $file_path;
    protected $segmented;
    protected $country;
    protected $error;
    
    public function __construct($publisher_org_id,$publisher_id,$api_key,$segmented = false)
    {
        $this->publisher_org_id = $publisher_org_id;
        $this->publisher_id = $publisher_id;
        $this->api_key = $api_key;
        $this->segmented = $segmented;
        
        $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', APPLICATION_ENV);
        $this->file_path = $config->public_folder.$config->xml_folder;
    }
    
    
    public function publish()
    {
        $activitiesCollection = $this->getDataToPublish();
        if($this->segmented){
            // reset existing published info
            $this->resetPublishedInfo();
            
            //print each segments activities xml and save published info
            foreach ($activitiesCollection as $org=>$activities)
            {
                $this->recipient = $org;
                $filename = $this->saveActivityXml($activities);
                $this->savePublishedInfo($filename);
                
                $this->publishToRegistry($activities,$filename);
            }
            
        } else {
            // remove existing published info
            $this->resetPublishedInfo();
            
            $filename = $this->saveActivityXml($activitiesCollection);
            $this->savePublishedInfo($filename);
            
            $this->publishToRegistry($activitiesCollection,$filename);
        }
    }
    
    
    public function publishToRegistry($activitiesCollection,$filename)
    {
        $registry = new Iati_Registry($activitiesCollection , $this->publisher_id , $this->api_key , $this->filepath.$filename);
        if($this->country){
            $registry->setCountryName($this->country);
        }
        $registry->prepareRegistryData();
        $registry->publishToRegistry();
        if($registry->getErrors()){
            $this->error = $registry->getErrors();
        }
    }
    
    public function getDataToPublish()
    {
        $oActivitycollection = new Iati_ActivityCollection();
        $activities = $oActivitycollection->getPublishedActivityCollection($this->publisher_org_id);
        if($this->segmented) {
            /**
             *Seperate activities by country or region
             *
             *foreach activity
             *if number of country is 1 i.e count of country is one and size is not zero, use country code 
             *else
             *  if number of region is 1 i.e count of region is one and size is not zero, use region code 
             *  else if number of regions is greater than 1 , use 998
             *  else
             *      if number of countries is 0, use 998
             *      else use the country with max percentage, if no percentage use first inserted.
            **/
            $segmented_activities = array();
            foreach($activities as $activity)
            {
                $countries = $activity->getElementsByType(Iati_Activity_Element::TYPE_RECIPIENT_COUNTRY);
                $countryAttribs = $countries[0]->getAttribs();
                if(1 == sizeof($countries) && 0 != sizeof($countryAttribs)){
                    $segmented_activities[$countries[0]->getAttribValue('@code')][] = $activity;
                    $this->country = $countries[0]->getAttribValue('@code');
                } else {
                    $regions = $activity->getElementsByType(Iati_Activity_Element::TYPE_RECIPIENT_REGION);
                    $regionAttribs = $regions[0]->getAttribs();
                    if(1 == sizeof($regions) && 0 != sizeof($regionAttribs)){
                        $segmented_activities[$regions[0]->getAttribValue('@code')][] = $activity;
                    } else if(sizeof($regions) > 1) {
                        $segmented_activities[998][] = $activity;
                    } else {
                        if(1 == sizeof($countries)){
                            $segmented_activities[998][] = $activity;
                        } else {
                            $maxPercent = '';
                            foreach($countries as $country){
                                $percent = $country->getAttrib('@percentage');
                                if($percent > $maxPercent){
                                    $maxPercent = $percent;
                                    $maxPercentCountry = $country->getAttribValue('@code');
                                }
                            }
                            if($maxPercentCountry){
                                $segmented_activities[$maxPercentCountry][] = $activity;
                            } else {
                                $segmented_activities[$countries[0]->getAttribValue('@code')][] = $activity;
                            }
                        }
                    }
                }                
            }
            return $segmented_activities;
            
        } else {
            return $activities;
        }
    }
    
    
    public function saveActivityXml($activities)
    {
        $oXmlHandler = new Iati_WEP_XmlHandler($activities);
        $file = strtolower($this->publisher_id);
        if($this->segmented){
            $file .= "-".strtolower($this->recipient);
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
                    'published_date' => date('Y-m-d h:i:s'),
                    'status' => 1
                    );
        $db->savePublishedInfo($data);
    }
    
    
    public function resetPublishedInfo()
    {
        $modelPublished = new Model_Published();
        $modelPublished->resetPublishedInfo($this->publisher_org_id);
    }
    
    public function getError()
    {
        return $this->error;
    }
}