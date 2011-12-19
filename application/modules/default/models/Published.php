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
        $this->update(array('status'=> 0),$where);
    }
    
    public function getPublishedInfo($accountId)
    {
        $rowSet = $this->select()
            ->where("publishing_org_id = ?",$accountId)
            ->where("status = 1");
        $result = $this->fetchAll($rowSet)->toArray();
        return $result;
    }
    
    public function getAllPublishedInfo($accountId)
    {
        $rowSet = $this->select()
            ->where("publishing_org_id = ?",$accountId);
        $result = $this->fetchAll($rowSet)->toArray();
        return $result;
    }
    
    public function deleteByFileId($fileId)
    {
        $this->delete(array('id = ?'=>$fileId));
    }
    
    public function deleteByAccountId($accountId)
    {
        $this->delete(array('id = ?'=>$fileId));
    }
}