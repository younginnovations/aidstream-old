<?php

class Model_CategoryManager extends Zend_Db_Table_Abstract
{

    protected $_name;

    public function getCategory($tblName, $catid, $lang, $flag=True)
    {

        $this->_name = $tblName;
        if (isset($catid))
            $rowSet = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
                            ->where('id = ?', $catid)
                            ->where('lang_id = ?', $lang);
        else
            $rowSet = $this->select()
                            ->where('lang_id = ?', $lang);
        $result[0] = $this->fetchAll($rowSet);
        $result[1] = $this->_getCols($rowSet);
        if ($result[0]->toArray() || $flag == FALSE) {
            return $result;
        }
        else
            $rowSet = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
                            ->where('lang_id = 1');

        $result[0] = $this->fetchAll($rowSet);

        return $result;
    }

    public function getByCategory($tblName, $cat, $lang)
    {

        $this->_name = $tblName;

        $rowSet = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
                        ->where('Code = ?', $cat)
                        ->where('lang_id = ?', $lang);
        $result[0] = $this->fetchAll($rowSet);
        $result = $result[0]->toArray();
        //$result[1] = $this->_getCols($rowSet);
        return $result;
    }

}

?>
