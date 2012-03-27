<?php
/**
 * 
 * Class to handle file register in Iati Registry. Works as a wrapper class for Ckan_Client class.
 * @author bhabishyat
 *
 */
class Iati_Registry
{
    protected $activities;
    protected $publisher_id;
    protected $api_key;
    protected $file;
    protected $file_id;
    protected $file_url;
    protected $is_pushed_to_registry;
    protected $json_data;
    protected $activity_updated_datetime;
    protected $activity_count;
    protected $country;
    protected $error;
    
    
    public function __construct($publisherId , $apiKey)
    {
        $this->publisher_id = $publisherId;
        $this->api_key = $apiKey;
    }
    
    /**
     * 
     * Generate the data to be feed to the registry.
     * @param Array $fileInfo, example 
     * array(
     * 'file_id' => '1', 
     * 'filename' => 'test.xml' , 
     * 'activity_count' => 1 , 
     * 'data_updated_datetime' => '2011-01-01 01:01:01 , 
     * 'pushed_to_registry' => 1
     * )
     */
    public function prepareRegistryData($fileInfo)
    {
        $this->file_id = $fileInfo['id'];
        $this->file = $fileInfo['filename'];
        $this->activity_count = $fileInfo['activity_count'];
        $this->activity_updated_datetime = $fileInfo['data_updated_datetime'];
        $this->is_pushed_to_registry = $fileInfo['pushed_to_registry']; 
        
        
        //prepare file's url
        $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', APPLICATION_ENV);
        $fc = Zend_Controller_Front::getInstance();
        $baseUrl =  $fc->getBaseUrl();
        $this->file_url = "http://".$_SERVER['SERVER_NAME']. $baseUrl . $config->xml_folder . $this->file;
        
        $this->_prepareRegistryInputJson();
    }
    
    /**
     * 
     * Generate json input to feed to the registry.
     */
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
    
    /**
     * 
     * Function to publish data to registry. The function checks for file saved in local database. 
     * If it is present the file is updated if not it is created in the IATI Registry. 
     */
    public function publishToRegistry()
    {
        $filename =explode('.',$this->file);
        $tmpfile = $filename[0];
        //$isPublished = $this->isFilePublished($tmpfile);

        $ckan = new Ckan_Client($this->api_key);
        try{            
            if($this->is_pushed_to_registry){
                $response = $ckan->put_package_entity($tmpfile,$this->json_data);
                if($response){
                    $this->saveRegistryPublishInfo($response,true);
                } else {
                    $this->error = "Your file could not be published in IATI Registry";
                    $this->error .= " {$ckan->getError()}";
                }
            } else{
                $response = $ckan->post_package_register($this->json_data);
                if($response){
                    $this->saveRegistryPublishInfo($response);
                } else {                    
                    $this->error = "Your files could not be published in IATI Registry.";
                    $this->error .= " {$ckan->getError()}";
                }
            }
             
        } catch (Exception $e) {
            print '<p><strong>Caught exception on Publishing to Registry: ' . $e->getMessage() . '</strong></p>';
            exit;
        }
        unset($ckan);
    }
    
    /**
     * 
     * Save the published info in local database.
     * @param $response			The actual response received for the registry.
     * @param Boolen $update	If true the response is saved as an insert operation, 
     * 							if false it is saved as an update.
     */
    public function saveRegistryPublishInfo($response ,$update = false)
    {
        $model = new Model_RegistryPublishedData();
        if($update){
            $model->updateRegistryPublishInfo($this->file_id , $response);
        } else {
            $model->saveRegistryPublishInfo($this->file_id , $response);
        }
    }
    
    /**
     * @deprecated
     * 
     * Checks if a file with given name is published against the local database.
     * @param String $filename	Name of file to check for published.
     * @return Boolen
     */
    public function isFilePublished($filename)
    {
        $model = new Model_RegistryPublishedData();
        $isPublished = $model->isFilePublished($filename);
        return $isPublished;
    }
    
    /**
     * 
     * Sets country name to be used in the json data.
     * @param $country
     */
    public function setCountryName($country)
    {
        $this->country = $country;
    }

    /**
     * 
     * @return Errors generated during publishing.
     */
    public function getErrors()
    {
        return $this->error;
    }
}