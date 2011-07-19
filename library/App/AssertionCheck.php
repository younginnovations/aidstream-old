<?php

class App_AssertionCheck extends Zend_Db_Table_Abstract
{

    protected $_name = 'Privilege';

    public function resourceCheck($userId, $resource)
    {
        $select = $this->select()->where('owner_id = ?', $userId);
        $row = $this->fetchRow($select);
        if ($row) {
            $row = $row->toArray();
            $unserializedRow = unserialize($row['resource']);
            $inArray = in_array($resource, $unserializedRow);
            return $inArray;
        }
        return FALSE;
    }

}