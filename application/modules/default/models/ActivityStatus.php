<?
class Model_ActivityStatus extends Zend_Db_Table_Abstract
{
    protected $_name = 'iati_activity';
    
    public function updateActivityStatus($activities_id,$status_id)
    {
        if($status_id == Iati_WEP_ActivityState::STATUS_PUBLISHED)
        {
            $this->publish($activities_id);
        }
        parent::update(array('status_id'=>$status_id),array('id IN(?)'=>$activities_id));
    }
    
    public function getActivityStatus($activity_id)
    {
        $rowSet = $this->select()->where('id=?',$activity_id);
        $activity = $this->fetchAll($rowSet)->toArray();
        return $activity[0]['status_id'];
    }
    
    public function publish($activities_id)
    {
        $activities = array();
        if($activities_id){
            $dbLayer = new Iati_WEP_DbLayer();
            foreach($activities_id as $activity_id)
            {
                $activity = $dbLayer->getRowSet('Activity', 'id', $activity_id, true, true);
                $activities[] = $activity;
            }
            $oXmlHandler = new Iati_WEP_XmlHandler($activities);
            $fp = fopen('/var/www/temp.xml','w');
            fwrite($fp,$oXmlHandler->getXmlOutput());
        }
    }
}