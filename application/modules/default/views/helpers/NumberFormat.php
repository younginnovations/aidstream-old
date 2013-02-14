<?php

class Zend_View_Helper_NumberFormat extends Zend_View_Helper_Abstract
{

    function numberFormat()
    {
        return $this;
    }
    
    /**
     * Localized Number Based On Defined Pattern
     * @param type $value
     * @return type $formatedNumber
     */
    public function localization($value)
    {   
        $pattern = array('number_format' => '##,##0.00');
        $formatedNumber = Zend_Locale_Format::toNumber($value,$pattern);
        return $formatedNumber;
    }

}
