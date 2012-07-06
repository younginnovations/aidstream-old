<?php
class Simplified_Model_DbTable_FundingOrg extends Zend_Db_Table_Abstract
{
    protected $_name = 'iati_participating_org';
    
    public function getFundingOrgsByActivityId($activityId)
    {
        $select = $this->select()
            ->from($this , array('id' , 'text'))
            ->where('activity_id = ?' , $activityId)
            ->where('`@role` = ?' , '1');
        return $this->fetchAll($select)->toArray();       
    }
    
    public function deleteFundingOrgsByIds($ids)
    {
        $where = $this->getAdapter()->quoteInto('id IN (?)', $ids);
        $this->delete($where);
    }
}