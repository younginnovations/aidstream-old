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
            if ($row['resource']) {
                $defaultArray = unserialize($row['resource']);
            }
            foreach ($defaultArray->getProperties() as $key => $eachData) {
                if ($key == $resource && $eachData == 1)
                    return TRUE;
            }
        }
        return FALSE;
    }

}