<?php

class Iati_Registry
{
    protected $activities;
    protected $publisher_id;
    protected $api_key;
    protected $file;
    protected $file_url;
    protected $json_data;
    protected $activity_updated_datetime;
    protected $activity_period_from;
    protected $activity_period_to;
    protected $activity_count;
    protected $country;
    protected $error;
    
    
    public function __construct($activities , $publisherId , $apiKey, $file)
    {
        $this->activities = $activities;
        $this->publisher_id = $publisherId;
        $this->api_key = $apiKey;
        $this->file = $file;
        $this->recipient = $recipient;
    }
    
    
    public function prepareRegistryData()
    {        
        //prepare file's url
        $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', APPLICATION_ENV);
        $fc = Zend_Controller_Front::getInstance();
	$baseUrl =  $fc->getBaseUrl();
        $this->file_url = "http://".$_SERVER['SERVER_NAME']. $baseUrl . $config->xml_folder . $this->file;
        
        $this->activity_count = sizeof($this->activities);
        
        foreach ($this->activities as $activity){
            $this->activity_updated_datetime = (strtotime($activity->getAttrib('@last_updated_datetime')) > strtotime($this->activity_updated_datetime))?$activity->getAttrib('@last_updated_datetime'):$this->activity_updated_datetime;
            $attribs = $activity->getAttribs();
        }
        
        $this->_prepareRegistryInputJson();
    }
    
    
    protected function _prepareRegistryInputJson()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $filename =explode('.',$this->file);
        
        
        $data = array(
            "name"=>$filename[0],
            "title"=>'Activity File '.$filename[0],
            "author_email"=>$identity->email,
            "resources" => array(
                                array(
                                    "url"=>$this->file_url,
                                    "format"=>"IATI-XML"
                                    )
                                ),
            "extras"=>array(
                    "filetype" => "activity",
                    "country" => $this->country,
                    "activity_period-from" => '',
                    "activity_period-to" => '',
                    "data_updated"=> date('Y-m-d',strtotime($this->activity_updated_datetime)),
                    "record_updated" => date('Y-m-d'),
                    "activity_count" => $this->activity_count,
                    "verified" => "no",
                    "language" => "en",
                    ),
            "groups" => array($this->publisher_id)
        );
        $jsonData =  json_encode($data);
        $this->json_data = $jsonData;
    }
    
    
    public function publishToRegistry()
    {
        $filename =explode('.',$this->file);
        $tmpfile = $filename[0];
        $isPublished = $this->isFilePublished($tmpfile);
        
        $ckan = new Ckan_Client($this->api_key);
        try{
            if($isPublished){
                $response = $ckan->put_package_entity($tmpfile,$this->json_data);
                if($response){
                    $this->saveRegistryPublishInfo($response,true);
                } else {
		    $this->error = "Sorry your file could not be pushed to registry";
		}
            } else{
                $response = $ckan->post_package_register($this->json_data);
                if($response){
                    $this->saveRegistryPublishInfo($response);
                } else {
		    $this->error = "Your files could not be published in IATI Registry";
		}
            }
           
        } catch (Exception $e) {
                print '<p><strong>Caught exception on Publishing to Registry: ' . $e->getMessage() . '</strong></p>';
                exit;
        }
        unset($ckan);
    }
    
    
    public function saveRegistryPublishInfo($response,$update = false)
    {
        $model = new Model_RegistryPublishedData();
        if($update){
            $model->updateRegistryPublishInfo($response);
        } else {
            $model->saveRegistryPublishInfo($response);
        }
    }
    
    
    public function isFilePublished($filename)
    {
        $model = new Model_RegistryPublishedData();
        $isPublished = $model->isFilePublished($filename);
        return $isPublished;
    }
    
    public function setCountryName($country)
    {
        $this->country = $country;
    }
    
    public function getErrors()
    {
	return $this->error;
    }
}