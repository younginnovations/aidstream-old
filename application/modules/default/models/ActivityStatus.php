<?
class Model_ActivityStatus extends Zend_Db_Table_Abstract
{
    protected $_name = 'iati_activity';
    
    public function updateActivityStatus($activities_id,$status_id)
    {
        //parent::update(array('status_id'=>$status_id),array('id IN(?)'=>$activities_id));
    }
    
    public function getActivityStatus($activity_id)
    {
        $rowSet = $this->select()->where('id=?',$activity_id);
        $activity = $this->fetchAll($rowSet)->toArray();
        return $activity[0]['status_id'];
    }
}