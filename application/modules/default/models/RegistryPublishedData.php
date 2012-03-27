<?php
class Model_RegistryPublishedData extends Zend_Db_Table_Abstract
{
    protected $_name = 'registry_published_data';
    
    public function saveRegistryPublishInfo($fileId , $response)
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $data['filename'] = $response->name;
        $data['file_id'] = $fileId;
        $serialisedResponse = serialize($response);
        $data['response'] = $serialisedResponse;
        $data['publisher_org_id'] = $identity->account_id;
        $this->insert($data);
        // Update published data.
        $modelPublish = new Model_Published();
        $modelPublish->markAsPushedToRegistry($fileId);
    }
    
    public function updateRegistryPublishInfo($fileId , $response)
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $serialisedResponse = serialize($response);
        $data['filename'] = $response->name;
        $data['file_id'] = $fileId;
        $data['response'] = $serialisedResponse;
        $data['publisher_org_id'] = $identity->account_id;
        $this->update($data,array('filename = ?'=>$response->name));
        // Update published data.
        $modelPublish = new Model_Published();
        $modelPublish->markAsPushedToRegistry($fileId);
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