<?
class Model_ActivityStatus extends Zend_Db_Table_Abstract
{
    protected $_name = 'iati_activity';
    
    public function updateActivityStatus($activity_id,$status_id)
    {
        parent::update(array('status_id'=>$status_id),array('id IN(?)'=>$activity_id));
    }
    
}