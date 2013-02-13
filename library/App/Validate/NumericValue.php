<?php

class App_Validate_NumericValue extends Zend_Validate_Abstract
{
    const NUMERIC = 'float';
 
    protected $_messageTemplates = array(
        self::NUMERIC => "Value is not numeric"
    );
 
    public function isValid($value)
    {
        $this->_setValue($value);
        $value = str_replace(",","",$value);        
        $count = substr_count($value, '.');
        if (!is_numeric($value) || $count > 1) {
            $this->_error(self::NUMERIC);
            return false;
        }
        return true;
    }
}