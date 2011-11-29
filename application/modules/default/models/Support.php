<?php

class Model_Support extends Zend_Db_Table_Abstract
{
    protected $_name = 'support';
    
    public function saveSupportRequest($data)
    {
        $input['name'] = $data['support_name'];
        $input['email'] = $data['support_email'];
        $input['query'] = $data['support_query'];
        $input['type'] = $data['support_type'];
        
        return parent::insert($input);

    }
}
?>