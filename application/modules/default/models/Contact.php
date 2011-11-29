<?php

class Model_Contact extends Zend_Db_Table_Abstract
{
    protected $_name = 'Contact';
    
    public function insert($feedbackdata)
    {
        $data['name'] = $feedbackdata['name'];
        $data['email'] = $feedbackdata['email'];
        $data['message'] = $feedbackdata['message'];
        
        return parent::insert($data);

    }
}
?>