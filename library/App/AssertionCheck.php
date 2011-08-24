<?php

class App_AssertionCheck extends Zend_Db_Table_Abstract
{

    protected $_name = 'Privilege';

    public function resourceCheck($userId, $resource)
    {
        $select = $this->select()->where('owner_id = ?', $userId);
        $rows = $this->fetchAll($select);
        if ($rows) {
            $rows = $rows->toArray();
            foreach($rows as $row){
            	$unserializedRow = unserialize($row['resource']);
            	$inArray = in_array($resource, $unserializedRow);
            	if($inArray)
            	return true;
            }
        }
        return FALSE;
    }

}