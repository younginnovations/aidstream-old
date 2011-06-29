<?php

class Model_Codelist extends Zend_Db_Table_Abstract
{

    protected $_name = 'CodeLists';

    

    public function listCodeIndex($lang =1)
    {        
         $rowSet = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
                 ->where('lang_id = ?', $lang)
                 ->order('codelist_id ASC');         
         $result = $this->fetchAll($rowSet);
         return $result;

        
    }
}

?>
