<?php

class Model_Help extends Zend_Db_Table_Abstract
{
    protected $_name = 'Help';
    
    public function getHelpMessage($element)
    {
        $select = $this->select()
            ->from($this,array('message'))
            ->where('element_name = ?',$element);
        return $this->fetchRow($select);
    }
    /**
     * @todo follow consistent naming for models in modules one is inside db other is on models dir itself
     */
}
