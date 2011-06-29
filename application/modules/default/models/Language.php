<?php

class Model_Language extends Zend_Db_Table_Abstract
{

    protected $_name = 'Lang';

    public function getLanguage()
    {
        $rowSet = $this->select();
        $result = $this->fetchAll($rowSet);
        return $result->toArray();
    }

    public function getDefaultLanguage()
    {
        $lang = 'en';
        $rowSet = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
                        ->where('Lang = ?', $lang);

        $result = $this->fetchAll($rowSet);
        $result = $result->toArray();
        return $result[0]['id'];
    }

    public function checkLanguage($lang)
    {
        $rowSet = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
                        ->where('id = ?', $lang);
        $result = $this->fetchAll($rowSet);
        if (!$result->toArray()) {
            return false;
        } else {
            return TRUE;
        }
    }

}

?>
