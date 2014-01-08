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
        $where = array(
            'publishing_org_id = ?'=> $data['publishing_org_id'],
            'filename = ?'=>$data['filename'] ,
        );
        $updated = $this->update($data, $where);
        if(!$updated){
            $this->_insertPublishedInfo($data);
        }
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
        $rowSet = $this->select()->setIntegrityCheck(false)
            ->from("$this->_name as pub" , 'pub.*')
            ->joinLeft('registry_published_data as rpd', 'pub.id = rpd.file_id' ,  'rpd.response')
            ->where("pub.publishing_org_id = ?",$accountId)
            ->order('pub.published_date DESC');
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

    /**
     * @param array $ids    Array of file ids.
     */
    public function getPublishedInfoByIds($ids)
    {
        $query = $this->select()
            ->where('id IN (?) ' , $ids);
        return $this->fetchAll($query)->toArray();
    }

    public function markAsPushedToRegistry($fileId)
    {
        $this->update(array('pushed_to_registry'=> '1') , array('id = ?' => $fileId));
    }

    public function getActivitiesByOrgId($accountId) {
        $rowSet = $this->select()
            ->where("publishing_org_id = ?",$accountId)
            ->where("pushed_to_registry = 1");
        $result = $this->fetchAll($rowSet)->toArray();
        return $result;
    }

    public function isPushedToRegistry($fileId) {
        $rowSet = $this->select()
            ->where("id = ?",$fileId)
            ->where("pushed_to_registry = 1");
        $result = $this->fetchRow($rowSet);
        if(count($result)) {
            return true;
        } else {
            return false;
        }
    }

}