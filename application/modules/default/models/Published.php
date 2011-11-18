<?php

class Model_Published extends Zend_Db_Table_Abstract
{
    protected $_name = 'published';
    
    protected function _insertPublishedInfo($data)
    {
        return parent::insert($data);
    }
    
    public function savePublishedInfo($data)
    {
        $this->_insertPublishedInfo($data);
    }
    
    public function resetPublishedInfo($publishingOrgId)
    {
        $where = array(
            'publishing_org_id = ?'=>$publishingOrgId,
        //    'filename = ?'=>$data['filename'] ,
        );
        $this->delete($where);
    }
    
    public function getPublishedInfo($account_id)
    {
        $rowSet = $this->select()
            ->where("publishing_org_id = ?",$account_id);
        $result = $this->fetchAll($rowSet)->toArray();
        return $result;
    }
    
    public function getInfo()
    {
        
    }
    
}