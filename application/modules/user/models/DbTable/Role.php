<?php

class User_Model_DbTable_Role extends Zend_Db_Table_Abstract {

    protected $_name = 'role';

    public function getRoleById($roleid) {
        $query = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
                        ->where('role_id = ?', $roleid);
        $result = $this->fetchRow($query);

        return $result;
    }

}

?>
