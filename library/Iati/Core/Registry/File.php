<?php

class Iati_Core_Registry_File
{
    protected $id;
    protected $name;
    protected $url;
    protected $isPushedToRegistry;
    protected $dataUpdatedDatetime;
    protected $activityCount;
    protected $countryCode;
    protected $countryName;
    protected $isOrganisationData = false;
    
    public function __construct($fileInfo = array())
    {
        if(is_array($fileInfo)){
            $this->setData($fileInfo);
        }
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function setFileName($filename)
    {
        $this->name = $filename;
    }
    
    public function setActivityCount($count)
    {
        $this->activityCount = $count;
    }
    
    public function setDataUpdatedDatetime($datetime)
    {
        $this->dataUpdatedDatetime = $datetime;
    }
    
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
        
        if(!$countryCode) return;
        
        if(is_numeric($this->countryCode)){
            $this->countryName = Model_Registry::getRegionNameFromCode($this->countryCode);
        } else {
            $this->countryName = Model_Registry::getCountryNameFromCode($this->countryCode);
        }
    }
    
    public function setPushedToRegistry($pushed = false)
    {
        $this->isPushedToRegistry = ($pushed) ? true : false ;
    }
    
    public function setIsOrganisationData()
    {
        $this->isOrganisationData = true;
    }
    
    public function isOrganisationData()
    {
        return $this->isOrganisationData;
    }
    
    public function set($attr , $value)
    {
        $this->$attr = $value;
    }
    
    public function get($attr)
    {
        return $this->$attr;
    }
    
    
    
    /**
     * Function to set the attributes of the file.
     * @param Array $fileInfo array containing the file information.
     */
    public function setData($fileInfo)
    {
        $this->setId($fileInfo['id']);
        $this->setFileName($fileInfo['filename']);
        $this->setActivityCount($fileInfo['activity_count']);
        $this->setDataUpdatedDatetime($fileInfo['data_updated_datetime']);
        $this->setCountryCode($fileInfo['country_name']);
        $this->setPushedToRegistry($fileInfo['pushed_to_registry']);
        
        //prepare file's url
        $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', APPLICATION_ENV);
        $fc = Zend_Controller_Front::getInstance();
        $baseUrl =  $fc->getBaseUrl();
        $this->set('url' , "http://".$_SERVER['SERVER_NAME']. $baseUrl . $config->xml_folder . $this->name);        
    }
    
    /**
     * Save the response received from the registry and update the is_published attribute of the file
     */
    public function saveRegistryPublishInfo($response)
    {   
        if($this->isOrganisationData()){
            $model = new Model_OrganisationRegistryPublishedData();
        } else { 
            $model = new Model_RegistryPublishedData();
        }
        
        if($this->isPushedToRegistry){
            $model->updateRegistryPublishInfo($this->id , $response);
        } else {
            $filename =explode('.', $this->name);
            $name = $filename[0];
            $model->saveRegistryPublishInfo($this->id , $name , $response);
        }
    }
}