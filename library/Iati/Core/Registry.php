<?php

class Iati_Core_Registry
{
    protected $publisherId;
    protected $apiKey;
    protected $publisherInfo;
    protected $file;
    protected $apiClient;
    protected $metadataGenerator;
    protected $error;
    protected $response;
    protected $version = 1.01;


    public function __construct($publisherId , $apiKey , $publisherInfo)
    {
        $this->setPublisherId($publisherId);
        $this->setApiKey($apiKey);
        $this->setPublisherInfo($publisherInfo);        
    }
    
    public function initializeAdapters()
    {
        // Generate json as per registry version
        $version = preg_replace('/\./' , '' , $this->version);
        switch($version){
            case '102' :
                $this->metadataGenerator = new Iati_Core_Registry_MetadataGenerator_102();
                $this->apiClient = new Iati_Core_Registry_Client($this->apiKey);
                break;
            case '101' :
            default :
                $this->metadataGenerator = new Iati_Core_Registry_MetadataGenerator_101();
                $this->apiClient = new Ckan_Client($this->apiKey);
                break;
        }        
    }
    
    public function setPublisherId($id)
    {
        $this->publisherId = $id;
    }
    
    public function setPublisherInfo($publisherInfo)
    {
        $this->publisherInfo = $publisherInfo;
    }
    
    
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }
    
    public function setRegistryFile(Iati_Core_Registry_File $file)
    {
        $this->file = $file;
    }
    
    public function setApiClient($client)
    {
        $this->apiClient = $client;
    }
    
    public function setVersion($version)
    {
        $this->version = $version;
        $this->initializeAdapters();
    }
    
    public function getPublisherInfo($infoName)
    {
        return ($infoName) ? $this->publisherInfo[$infoName] : $this->publisherInfo;
    }
    
    /**
     *
     * @return Errors generated during publishing.
     */
    public function getErrors()
    {
        return $this->error;
    }
    
    public function publishToRegistry(Iati_Core_Registry_File $file = null)
    {
        if($file){
            $this->setRegistryFile($file);
        }

        if(!$this->apiClient || $this->metadataGenerator) $this->initializeAdapters();

        if($this->file->isOrganisationData()){
            $json = $this->metadataGenerator
                        ->prepareOrganisationRegistryInputJson(
                                                                $this->publisherId ,
                                                                $this->publisherInfo ,
                                                                $this->file
                                                            );
        } else {
            $json = $this->metadataGenerator
                        ->prepareRegistryInputJson($this->publisherId , $this->publisherInfo , $this->file);
        }
        $this->pushToRegistry($json);
    }
    
    public function pushToRegistry($json)
    {
        $filename =explode('.',$this->file->get('name'));
        $tmpfile = $filename[0];

        try{
            if($this->file->get('isPushedToRegistry')){
                $response = $this->apiClient->put_package_entity($tmpfile, $json);
            } else{
                $response = $this->apiClient->post_package_register($json);
            }
            
            if($response){
                $this->file->saveRegistryPublishInfo($response);
            } else {
                $this->error = "Your file could not be published in IATI Registry.";
                $this->error .= " {$this->apiClient->getError()}";
            }

        } catch (Exception $e) {
            print '<p><strong>Caught exception on Publishing to Registry: ' . $e->getMessage() . '</strong></p>';
            exit;
        }
        $this->response = $response;
    }
}