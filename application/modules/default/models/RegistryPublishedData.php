<?php
class Model_RegistryPublishedData extends Zend_Db_Table_Abstract
{
    protected $_name = 'registry_published_data';

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
        $modelPublish = new Model_Published();
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

    /**
     *
     * Function to count the total number of activities published using
     * the activity_count in extras field of the response
     * @param $publishedFiles
     */
    public function getActivityCount($publishedFiles)
    {
        $count = 0;
        foreach($publishedFiles as $file)
        {
            $response = unserialize($file['response']);
            $actCount = $response->extras->activity_count;
            if($actCount){
                $count += $response->extras->activity_count;    
            } else { // for ckan version 1.03 type response
                $extras = $response->result->extras;
                if(empty($extras)) continue;
                foreach($extras as $extra){
                    if($extra->key == 'activity_count'){
                        $count += $extra->value;
                    }
                }
            }
            
        }
        return $count;
    }

    public function getAllRegistryPublishedData() {
        $query = $this->select()->from($this, array('publisher_org_id', 'file_id', 'filename'));
        return $this->fetchAll($query);
    }



}