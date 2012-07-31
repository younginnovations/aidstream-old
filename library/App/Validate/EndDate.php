<?php

class App_Validate_EndDate extends Zend_Validate_Abstract
{
    const INVALID = 'notValid';
    protected $compareTo;
 
    protected $_messageTemplates = array(
        self::INVALID => "End Date should be greater than Start Date"
    );
    
    // If start field has other name pass it as parameter to constructer.
    public function __construct($compareTo = 'start_date')
    {
        // If form element is passed to compare with, set is as compare to use in comparision.
        if($compareTo instanceof Zend_Form_Element){
            $this->compareTo = $compareTo;
            return;
        }
        
        if ($compareTo instanceof Zend_Config) {
            $compareTo = $compareTo->toArray();
        }
        
        if (is_array($compareTo)) {
            if (array_key_exists('compareTo', $compareTo)) {
                $compareTo = $compareTo['compareTo'];
            } 
        }
        
        $this->compareTo = $compareTo ;
    }
    
    public function isValid($value , $context = null)
    {
        $this->_setValue($value);
        
        // If form element is passed for comparing during construct, compare with the elements value
        if($this->compareTo instanceof Zend_Form_Element){
            var_dump($this->compareTo->getValue());
            if($this->compareTo->getValue() && (strtotime($this->compareTo->getValue()) < strtotime($value))){
                return true;
            } else {
                $this->_error(self::INVALID);
                return false;
            }
        }
        
        if(isset($context[$this->compareTo]) && (strtotime($context[$this->compareTo]) < strtotime($value))){
            return true;
        } else {
            $this->_error(self::INVALID);
            return false;
        }
    }
}