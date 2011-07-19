<?php
class Iati_WEP_DbLayer extends Zend_Db_Table_Abstract
{
    protected $_name;

    /**
     * Save data for the current object
     *
     *
     */
    public function save ($object) {
        $tableClassMapper = new Iati_WEP_TableClassMapper();
        $tablename = $tableClassMapper->getTableName($object->getClassName());
        if($tablename){
            $this->_name = $tablename;
            $data = $object->getData();
            if ($object->getPrimary()) {
                // try to update data with $tablename and id
                try{
                    return parent::update($data,array('id= ?' => $object->getPrimary()));
                }
                catch(Exception $e){
                    /*$object->setError(True);
                    $object->setErrorMessage($e);*/
                    return False;
                }
            }
            else {
                // try to insert data with $tablename
                try{
                    return parent::insert($data);
                }
                catch(Exception $e){
                    /*$object->setError(True);
                    $object->setErrorMessage($e);*/
                    return False;
                }
            }
        }
        //        else
        //            throw
    }
}