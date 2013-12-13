<?php
class Model_OrganisationRegistryPublishedData extends Zend_Db_Table_Abstract
{
    protected $_name = 'organisation_registry_published_data';

    public function saveRegistryPublishInfo($fileId , $filename , $response)
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $data['filename'] = $filename;
        $data['file_id'] = $fileId;
        $serialisedResponse = serialize($response);
        $data['response'] = $serialisedResponse;
        $data['publisher_org_id'] = $identity->account_id;
        $this->insert($data);
        // Update published data.
        $modelPublish = new Model_OrganisationPublished();
        $modelPublish->markAsPushedToRegistry($fileId);
    }

    public function updateRegistryPublishInfo($fileId , $response)
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $serialisedResponse = serialize($response);
        $data['response'] = $serialisedResponse;
        $this->update($data,array('file_id = ?'=>$fileId));
        // Update published data.
        $modelPublish = new Model_Published();
        $modelPublish->markAsPushedToRegistry($fileId);
    }

    /**
     *
     * @param String $filename
     */
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

    public function getPublishedInfoByOrg($org_id)
    {
        $rowSet = $this->select()->where('publisher_org_id = ?',$org_id);
        return $this->fetchAll($rowSet)->toArray();
    }
}