<?php

class Iati_ActivityCollection
{
    public function getPublishedActivityCollection($account_id)
    {
        $db = new Model_ActivityCollection();
        $activities_id = $db->getActivitiesByStatusAndAccount(Iati_WEP_ActivityState::STATUS_PUBLISHED, $account_id);
        $activities = array();
        $dbLayer = new Iati_WEP_DbLayer();
        foreach($activities_id as $activity_id)
        {
            $activity = $dbLayer->getRowSet('Activity', 'id', $activity_id['id'], true, true);
            $activity->setAttrib('id' , $activity_id['id'] );
            $activities[] = $activity;
        }
        return $activities;
    }
}