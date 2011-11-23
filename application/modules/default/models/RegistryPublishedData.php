<?php
class Model_RegistryPublishedData extends Zend_Db_Table_Abstract
{
    protected $_name = 'registry_published_data';
    
    public function saveRegistryPublishInfo($response)
    {
        $data['filename'] = $response->name;
        $serialisedResponse = serialize($response);
        $data['response'] = $serialisedResponse;
        $this->insert($data);
    }
    
    public function updateRegistryPublishInfo($response)
    {
        $serialisedResponse = serialize($response);
        $data['filename'] = $response->name;
        $data['response'] = $serialisedResponse;
        $this->update($data,array('filename = ?'=>$response->name));
    }
    
    public function isFilePublished($filename)
    {
        $query = $this->select()->where('filename = ?',$filename);
        $result =  $this->fetchRow($query);
        if($result){
            return true;
        } else {
            return false;
        }
    }
}