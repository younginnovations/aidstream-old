<?php

class Model_ActivityHash extends Zend_Db_Table_Abstract
{
    protected $_name = 'activity_hash';
    
    public function updateHashByActivityId($data)
    {
        $this->update($data,array('activity_id = ?'=>$data['activity_id']));
    }
    
    public function getByActivityId($activity_id)
    {
        $rowSet = $this->select()
                ->where('activity_id = ?',$activity_id);
        $activity = $this->fetchRow($rowSet);
        return $activity;
    }
    
    public function updateHash($activity_id)
    {
        $dbLayer = new Iati_WEP_DbLayer();
        $activity = $dbLayer->getRowSet('Activity', 'id', $activity_id, true, true);
        $activity->setattrib('@last_updated_datetime',null);
        $new_hash = sha1(serialize($activity));
        
        $data['activity_id'] = $activity_id;
        $data['hash'] = $new_hash;
        
        $has_hash = $this->getByActivityId($activity_id);
        if($has_hash){
            if($has_hash['hash'] === $new_hash){
                return false;
            } else {
                $this->updateHashByActivityId($data);
                return true;
            }
        } else {
            $this->insert($data);
            return true;
        }
    }
    
    public function updateActivityHash($activity_id)
    {
        $activityClassobj = new Iati_Aidstream_Element_Activity();
        $activityData = $activityClassobj->fetchData($activity_id , false);
        unset($activityData['Activity'][0]['@last_updated_datetime']);   
        $new_hash = sha1(serialize($activityData));
        
        $data['activity_id'] = $activity_id;
        $data['hash'] = $new_hash;
        
        $has_hash = $this->getByActivityId($activity_id);
        
        if($has_hash){
            if($has_hash['hash'] === $new_hash){
                return false;
            } else {
                $this->updateHashByActivityId($data);   
                return true;
            }
        } else {
            $this->insert($data);
            return true;
        }
    }
    
    public function deleteActivityHash($activity_id)
    {
        $this->delete(array('activity_id = ?'=>$activity_id));
    }
}