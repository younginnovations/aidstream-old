<?php

class App_Validate_Numeric extends Zend_Validate_Abstract
{
    const NUMERIC = 'float';
 
    protected $_messageTemplates = array(
        self::NUMERIC => "Value is not numeric"
    );
 
    public function isValid($value)
    {
        $this->_setValue($value);
 
        if (!is_numeric($value)) {
            $this->_error();
            return false;
        }
 
        return true;
    }
}