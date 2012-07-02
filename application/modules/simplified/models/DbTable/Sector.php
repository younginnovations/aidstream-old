<?php
class Simplified_Model_DbTable_Sector extends Zend_Db_Table_Abstract
{
    protected $_name = 'iati_sector';
    
    public function deleteSectorsByIds($ids)
    {
        $where = $this->getAdapter()->quoteInto('id IN (?)', $ids);
        $this->delete($where);
    }
}