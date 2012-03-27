<?php
/**
 * 
 * Class to generate xml files from the activities.
 * @author bhabishyat
 *
 */
class Iati_WEP_Publish
{
    protected $publisher_org_id;
    protected $publisher_id;
    protected $recipient;
    protected $file_path;
    protected $segmented;
    protected $country;
    protected $error;
    
    public function __construct($publisher_org_id , $publisher_id , $segmented = false)
    {
        $this->publisher_org_id = $publisher_org_id;
        $this->publisher_id = $publisher_id;
        $this->segmented = $segmented;
        
        $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', APPLICATION_ENV);
        $this->file_path = $config->public_folder.$config->xml_folder;
    }
    
    /**
     * 
     * Function to publish xml data. Calls internal functions to prepare and 
     * save xml file and save the published data to local database.
     */
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
                
                if(is_array($activities)){
                    foreach ($activities as $activity){
                        $activityUpdatedDatetime = (strtotime($activity->getAttrib('@last_updated_datetime')) > strtotime($activityUpdatedDatetime))?$activity->getAttrib('@last_updated_datetime'):$activityUpdatedDatetime;
                    }
                }
                
                $country = '';
                if(in_array($this->recipient , $this->country)){
                    $country = $this->recipient;
                }
                
                $this->savePublishedInfo($filename , $country , sizeof($activities) , $activityUpdatedDatetime);
                
            }
            
        } else {
            // remove existing published info
            $this->resetPublishedInfo();

            $filename = $this->saveActivityXml($activitiesCollection);
            
            if(is_array($activitiesCollection)){
                foreach ($activitiesCollection as $activity){
                    $activityUpdatedDatetime = (strtotime($activity->getAttrib('@last_updated_datetime')) > strtotime($activityUpdatedDatetime))?$activity->getAttrib('@last_updated_datetime'):$activityUpdatedDatetime;
                }
            }
            
            $this->savePublishedInfo($filename , '' , sizeof($activitiesCollection) , $activityUpdatedDatetime);
            
        }
    }
    
    /**
     * 
     * Function to fetch data from the local database and generates array of activities base on segmentation.
     */
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
                    $this->country[] = $countries[0]->getAttribValue('@code');
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
                                $this->country[] = $maxPercentCountry;
                            } else {
                                $segmented_activities[$countries[0]->getAttribValue('@code')][] = $activity;
                                $this->country[] = $countries[0]->getAttribValue('@code');
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
    
    /**
     * 
     * Creates xml file using Iati_WEP_Xmlhandler and saves them to local directory.
     * @param Array $activities	Array of activities to be published.
     */
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
        fclose($fp);
        
        return $filename;
    }
    
    /**
     * 
     * Save publish data to local database.
     * @param String $filename
     * @param String $country
     * @param Integer $activityCount
     * @param Datetime $dataLastUpdatedDate
     */
    public function savePublishedInfo($filename , $country , $activityCount , $dataLastUpdatedDate)
    {
        $db = new Model_Published();
        $data = array(
                    'publishing_org_id' => $this->publisher_org_id,
                    'filename' => $filename,
                    'activity_count' => $activityCount,
                    'country_name' => $country,
                    'data_updated_datetime' => $dataLastUpdatedDate,
                    'published_date' => date('Y-m-d h:i:s'),
                    'status' => 1
                    );
        $db->savePublishedInfo($data);
    }
    
    /**
     * 
     * Reset all published info for the publisher i.e change status of the published files.
     */
    public function resetPublishedInfo()
    {
        $modelPublished = new Model_Published();
        $modelPublished->resetPublishedInfo($this->publisher_org_id);
    }
    
    /**
     * 
     * @return	Returns error generated during publish.
     */
    public function getError()
    {
        return $this->error;
    }
}