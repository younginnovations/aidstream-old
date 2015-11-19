<?php

class Model_ActivityCollection extends Zend_Db_Table_Abstract
{
    protected $_name = 'iati_activity';

    public function getActivitiesByStatus($status)
    {

    }

    /**
     * Function to  get arrray of activity ids for an organisation
     *
     * @param int $account_id   organisation id
     * @return array    array of activity ids associated with the account.
     */
    public function getActivityIdsByAccount($account_id)
    {
        $rowSet = $this->select()->setIntegrityCheck(false)
            ->from(array('iact'=>'iati_activity'),'iact.id')
            ->join(array('iacts'=>'iati_activities'),'iact.activities_id = iacts.id','')
            ->where('iacts.account_id=?',$account_id);
        return $this->fetchAll($rowSet)->toArray();
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

    public function getActivitiesCountByAccount($account_id)
    {
        $rowSet = $this->select()->setIntegrityCheck(false)
            ->from(array('iact'=>'iati_activity'),'COUNT(iact.id) AS activity_count')
            ->join(array('iacts'=>'iati_activities'),'iact.activities_id = iacts.id','')
            ->where('iacts.account_id=?',$account_id);
        return $activities = $this->fetchAll($rowSet)->toArray();
    }

    public function getActivitiesByAccount($account_id)
    {
        $rowSet = $this->select()->setIntegrityCheck(false)
            ->from(array('iact'=>'iati_activity'),'*')
            ->join(array('iacts'=>'iati_activities'),'iact.activities_id = iacts.id','')
            ->where('iacts.account_id=?',$account_id);
        return $activities = $this->fetchAll($rowSet)->toArray();
    }

    /**
     *
     * Function to get the activity status and activity sectors information for dashboard.
     * @param Array $activities
     */
    public function getActivityAttribs($activities)
    {
        $model = new Model_Wep();
        $sectors = array();
        $iatiStatuses = $model->getRowsByFields('ActivityStatus', 'lang_id', 1);
        $status = array();
        foreach($activities as $activity){

            //Preapare sector data.
            $sectorData = $model->listAll('iati_sector', 'activity_id', $activity['id']);
            if($sectorData){
                foreach($sectorData as $sectorValue){
                    if(!$sectorValue['@vocabulary'] || $sectorValue['@vocabulary'] == 3 || $sectorValue['@vocabulary'] == 8){
			        if(!$sectorValue['@code']) continue;

                        if($sectorValue['@vocabulary'] == 8)
                            $sectorName = $model->fetchValueById('SectorDacThree', $sectorValue['@code'] , 'Name');
                        else
                            $sectorName = $model->fetchValueById('Sector', $sectorValue['@code'] , 'Name');


                        if(strlen($sectorName) > 35){
                            $sectorName = substr($sectorName, 0 , 32)."...";
                        }
                    } else {
                        $sectorName = $sectorValue['text'];
                        if(strlen($sectorName) > 35){
                            $sectorName = preg_replace('/ \w+$/', '', substr($sectorName, 0 , 32));
                            $sectorName .= "&hellip;";
                        }
                    }
                    if($sectorName){
                        $sectors[] = $sectorName;
                    }
                }
            }

            //Prepare activity status data.
            $statusData = $model->listAll('iati_activity_status', 'activity_id', $activity['id']);
            if($statusData){
	            foreach($statusData as $statusValue){
	               $status[$statusValue['@code']] += 1;
	            }
            } else {
                $status['unknown'] += 1;
            }
        }
        foreach($iatiStatuses as $iatiStatus){
            $iatiStatus['count'] = $status[$iatiStatus['Code']];
            $activityStatus[] = $iatiStatus;
        }
        if($status['unknown']){
            $activityStatus[] = array('Name' => 'Status Unspecified' , 'count' => $status['unknown']);
        }
        $output = array();
        $output['status'] = $activityStatus;
        $output['sector'] = array_unique($sectors);
        return $output;
    }
    
    public function getActivitiesCountByStatusAndAccount($status , $accountId)
    {
	$rowSet = $this->select()->setIntegrityCheck(false)
            ->from(array('iact'=>'iati_activity'),'count(*) as activityCount')
            ->join(array('iacts'=>'iati_activities'),'iact.activities_id = iacts.id','')
            ->where('iacts.account_id=?',$accountId)
            ->where('iact.status_id=?',$status);
        $activitiesCount = $this->fetchRow($rowSet)->activityCount;
        return $activitiesCount;
    }

    public function getActivityAccess($activityId, $accountId) {
        $activityList = array();
        $activityArray = $this->getActivityIdsByAccount($accountId);
        foreach ($activityArray as $activity) {
            array_push($activityList, $activity['id']);
        }
        if (in_array($activityId, $activityList)) {
            return true;
        }
        return false;
    }
}