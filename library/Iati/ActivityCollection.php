<?php

class Iati_ActivityCollection
{
    public function getPublishedActivityCollection($account_id)
    {
        $db = new Model_ActivityCollection();
        $activitiesId = $db->getActivitiesByStatusAndAccount(Iati_WEP_ActivityState::STATUS_PUBLISHED, $account_id);
        
        return $activitiesId;
    }
}