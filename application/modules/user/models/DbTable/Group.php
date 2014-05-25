<?php

class User_Model_DbTable_Group extends Zend_Db_Table_Abstract {

    protected $_name = 'group';

    public function insertGroupWithAccountId($accountId, $groupId) {
        $data['account_id'] = $accountId;
        $data['group_id'] = $groupId;
        $this->insert($data);
    }

    public function deleteGroup($groupId) {
        $where = $this->getAdapter()->quoteInto('group_id = ?', $groupId);
        return $this->delete($where);
    }

    public function getOrganisationIdByGroupId($groupId) {
        $query = $this->select()->from($this, array('account_id'))
                        ->where('group_id = ?', $groupId);
        $results = $this->fetchAll($query)->toArray();
        foreach ($results as $result) {
            $organisationIds[] = $result['account_id'];
        }
        return $organisationIds;
    }

    public function getAllOrganisationsByGroupId($groupId) {
        $query = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
            ->joinLeft(array('ac'=>'account'), 'group.account_id = ac.id')
            ->where('group_id = ?',$groupId);
        return $this->fetchAll($query)->toArray();
    }

    public function getOrganisationCountByGroupId($groupId) {
        $query = $this->select()->where('group_id = ?',$groupId);
        $result = $this->fetchAll($query);
        return count($result);
    }

}