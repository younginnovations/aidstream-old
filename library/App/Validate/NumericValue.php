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
        $incorrect = true;
        // Check if mutliple commas exist together
        $pos = strrpos($value, ",,");
        if($pos)
        {
            $incorrect = false;
        }    
        // Replace single commas with sapce
        $value = str_replace(",","",$value);  
        // Check if multiple dot exist
        $count = substr_count($value, '.');
        if($count > 1)
        {
            $incorrect = false;
        }
        // Check value is numeric or not
        if (!is_numeric($value)) {
            $incorrect = false;            
        }
        
        if(!$incorrect)
        {  
            $this->_error(self::NUMERIC);
            return false;
        }
        return $incorrect;
    }
}