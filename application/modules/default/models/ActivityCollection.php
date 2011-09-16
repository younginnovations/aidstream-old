<?php

class Model_ActivityCollection extends Zend_Db_Table_Abstract
{
    protected $_name = 'iati_activity';
    
    public function getActivitiesByStatus($status)
    {
        
    }
    
    public function getActivitiesByStatusAndAccount($status,$account_id)
    {
        $rowSet = $this->select()->setIntegrityCheck(false)
            ->from(array('iact'=>'iati_activity'),'iact.id')
            ->join(array('iacts'=>'iati_activities'),'iact.activities_id = iacts.id','')
            ->where('iacts.account_id=?',$account_id)
            ->where('iact.status_id=?',$status);
        $activities = $this->fetchAll($rowSet)->toArray();
        return $activities;
    }
    
}