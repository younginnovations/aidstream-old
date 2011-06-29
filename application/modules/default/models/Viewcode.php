<?php

class Model_Viewcode extends Zend_Db_Table_Abstract
{

    protected $_name;

    public function getCode($tblName, $codeid,$lang)
    {
        $this->_name = $tblName;
        if (isset($codeid))
        $rowSet = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
        ->where('id = ?', $codeid)
        ->where('lang_id = ?',$lang);
        else
        $rowSet = $this->select()
        ->where('lang_id = ?',$lang);

        $result[0] = $this->fetchAll($rowSet)->toArray();
        $result[1] = $this->_getCols($rowSet);
        return $result;
    }

    //    public function checkcode($colName)
    //    {
    //
    //        $columnName[0] = array(0 => "id",
    //            1 => "Code", 2 => "Name", 3 => "lang_id");
    //        $columnName[1] = array(0 => "id",
    //            1 => "Code", 2 => "Name", 3 => "Description", 4 => "lang_id");
    //        $columnName[2] = array(0 => "id",
    //            1 => "Code", 2 => "Name", 3 => "Description", 4 => "CategoryCode", 5 => "lang_id");
    //        $columnName[3] = array(0 => "id",
    //            1 => "Code", 2 => "Name", 3 => "CategoryCode", 4 => "lang_id");
    //    }

    public function filterField($colName)
    {
        $i = 0;
        foreach ($colName as $tmpName) {
            if ($tmpName != 'id' && $tmpName != 'lang_id')
            $fieldName[$i] = $tmpName;
            $i++;
        }
        return $fieldName;
    }

    public function update($data, $id, $tblName)
    {
        $this->_name = $tblName;
        //$data['lang_id']=$lang;
        parent::update($data, array('id = ?' => $id));
    }

    public function add($data, $tblName,$lang)
    {
        $this->_name = $tblName;
        $data['lang_id']= $lang;
        return parent::insert($data);
    }

    public function updateCategory($data, $catid, $tblName)
    {
        $tblName = str_replace('Category', '', $tblName);
        $this->_name = $tblName;
        parent::update($data, array('CategoryCode = ?' => $catid));
    }
    public function deleteCode($code,$tblName) {
        $this->_name = $tblName;
        $where = $this->getAdapter()->quoteInto('Code = ?', $code);
        $this->delete($where);
    }

    public function updateCode($data, $code, $tblName)
    {
        $this->_name = $tblName;
        // $data['lang_id']=$lang;
        parent::update($data, array('Code = ?' => $code));
    }

    public function findIdByFieldData($tblName, $data, $lang){
        $this->_name = $tblName;
        $rowSet = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
        ->where('Code = ?', $data)
        ->where('lang_id = ?',$lang);
        return $this->fetchAll($rowSet)->toArray();
    }


    /**
     * @author manisha@yipl.com.np
     */

    /**
     *
     *
     * @param $tblName
     * @param $fieldName
     * @param $data
     * @return array of data of the row
     */
    public function getRowsByFields($tblName, $fieldName, $data){
        $this->_name = $tblName;
        /* if (isset($codeid))
         $rowSet = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
         ->where('id = ?', $codeid)
         ->where('lang_id = ?',$lang);
         else*/
        $rowSet = $this->select()->where("$fieldName = ?",$data);

        $result = $this->fetchAll($rowSet)->toArray();
        //        $result[1] = $this->_getCols($rowSet);
        return $result;
    }

    public function insertRowsToTable($tblName, $data){
        $this->_name = $tblName;
        
        return parent::insert($data);
    }
}

?>
