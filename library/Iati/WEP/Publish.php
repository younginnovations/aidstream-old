<?php
/**
 * 
 * Class to generate xml files from the activities.
 * @author bhabishyat
 *
 */
class Iati_WEP_Publish
{
    protected $publisherOrgId;
    protected $publisherId;
    protected $recipient;
    protected $filePath;
    protected $segmented;
    protected $country;
    protected $error;
    
    public function __construct($publisherOrgId , $publisherId , $segmented = false)
    {
        $this->publisherOrgId = $publisherOrgId;
        $this->publisherId = $publisherId;
        $this->segmented = $segmented;
        
        $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', APPLICATION_ENV);
        $this->filePath = $config->public_folder.$config->xml_folder;
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
            foreach ($activitiesCollection as $org=>$activitiesId)
            {   
                $this->recipient = $org;
                $fileName = $this->saveActivityXml($activitiesId);
                
                if(is_array($activitiesId)){
                    $wepModel = new Model_Wep();
                    foreach ($activitiesId as $activityId){
                        $activityRow = $wepModel->getRowById('iati_activity', 'id', $activityId['id']);
                        $activityUpdatedDatetime = (strtotime($activityRow['@last_updated_datetime']) > strtotime($activityUpdatedDatetime))?$activityRow['@last_updated_datetime']:$activityUpdatedDatetime;
                    }
                }
                /*
                $country = '';
                if(in_array($this->recipient , $this->country)){
                    $country = $this->recipient;
                }
                */
                
                $this->savePublishedInfo($fileName , $org , sizeof($activities) , $activityUpdatedDatetime);
                
            }
            
        } else {
            // remove existing published info
            $this->resetPublishedInfo();

            $fileName = $this->saveActivityXml($activitiesCollection);
            
            if(is_array($activitiesCollection)){
                $wepModel = new Model_Wep();
                foreach ($activitiesCollection as $activityId){ 
                    $activityRow = $wepModel->getRowById('iati_activity', 'id', $activityId['id']);
                    $activityUpdatedDatetime = (strtotime($activityRow['@last_updated_datetime']) > strtotime($activityUpdatedDatetime))?$activityRow['@last_updated_datetime']:$activityUpdatedDatetime;
                }
            }
            
            $this->savePublishedInfo($fileName , '' , sizeof($activitiesCollection) , $activityUpdatedDatetime);
            
        }
    }
    
    /**
     * 
     * Function to fetch data from the local database and generates array of activities base on segmentation.
     */
    public function getDataToPublish()
    {
        $activityCollection = new Iati_ActivityCollection();
        $activitiesId = $activityCollection->getPublishedActivityCollection($this->publisherOrgId);
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
            $segmentedActivities = array();
            foreach($activitiesId as $activityId)
            {   
                $recipientCountryObj = new Iati_Aidstream_Element_Activity_RecipientCountry();
                $countries = $recipientCountryObj->fetchData($activityId ,true);
                $recipientRegionObj = new Iati_Aidstream_Element_Activity_RecipientRegion();
                $regions = $recipientRegionObj->fetchData($activityId ,true);
                
                if($countries[0]['@code'])
                {
                    $countryCode = Iati_Core_Codelist::getCodeByAttrib('RecipientCountry' , '@code' , $countries[0]['@code']);
                }
                if($regions[0]['@code'])
                {
                    $regionCode = Iati_Core_Codelist::getCodeByAttrib('RecipientRegion' , '@code' , $regions[0]['@code']);
                }
                
                
                if(count($countries) == 1)
                { 
                    $segmentedActivities[$countryCode][] = $activityId;
                }
                else
                {
                    if(count($regions) == 1)
                    {
                        $segmentedActivities[$regionCode][] = $activityId;
                    }
                    elseif(count($regions) > 1)
                    {
                        $segmentedActivities[998][] = $activityId;
                    }
                    else
                    {  
                        if(count($countries) == 0)
                        {   
                            $segmentedActivities[998][] = $activityId;
                        }
                        else
                        {   
                            $maxPercent = '';
                            foreach ($countries as $country)
                            {   
                                $percent = $country['@percentage'];
                                if($percent > $maxPercent){
                                    $maxPercent = $percent;
                                    $maxPercentCountry = Iati_Core_Codelist::getCodeByAttrib('RecipientCountry' , '@code' , $country['@code'],'Name');
                                }
                            }
                            if($maxPercentCountry){
                                $segmentedActivities[$maxPercentCountry][] = $activityId;
                            } else {
                                $segmentedActivities[$countryCode][] = $activityId;
                            }
                        }    
                    }
                }                   
            }
            return $segmentedActivities;
            
        } else {
            return $activitiesId;
        }
    }
    
    /**
     * 
     * Creates xml file using Iati_WEP_Xmlhandler and saves them to local directory.
     * @param Array $activities	Array of activities to be published.
     */
    public function saveActivityXml($activitiesIdArray)
    {
        $file = strtolower($this->publisherId);
        if($this->segmented){
            $file .= "-".strtolower($this->recipient);
        } else {
            $file .= "-activities";
        }
        $file = preg_replace('/ /','_',$file);
        $file = preg_replace('/,/','',$file);
        $fileName = $file.".xml";
        
        $obj = new Iati_Core_Xml();
        $fp = fopen($this->filePath.$fileName,'w');
        fwrite($fp,$obj->generateXml('Activity' ,$activitiesIdArray));
        fclose($fp);
        
        return $fileName;
    }
    
    /**
     * 
     * Save publish data to local database.
     * @param String $fileName
     * @param String $country
     * @param Integer $activityCount
     * @param Datetime $dataLastUpdatedDate
     */
    public function savePublishedInfo($fileName , $country , $activityCount , $dataLastUpdatedDate)
    {
        $db = new Model_Published();
        $data = array(
                    'publishing_org_id' => $this->publisherOrgId,
                    'filename' => $fileName,
                    'activity_count' => $activityCount,
                    'country_name' => $country,
                    'data_updated_datetime' => $dataLastUpdatedDate,
                    'published_date' => date('Y-m-d h:i:s'),
                    'status' => 1,
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
        $modelPublished->resetPublishedInfo($this->publisherOrgId);
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