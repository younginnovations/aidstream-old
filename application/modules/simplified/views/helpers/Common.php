<?php
class Zend_View_Helper_Common extends Zend_View_Helper_Abstract
{
    function common()
    {
        return $this;
    }
    
    function checkPlain($text) 
    {
        // return ($text) ? htmlspecialchars($text, ENT_QUOTES) : '';
        return $this->view->escape($text);
    }
    
    function htmlAttributes($attributes = array()) {
        if (is_array($attributes)) {
            $t = '';
            foreach ($attributes as $key => $value) {
                $t .= " $key=".'"'. $this->checkPlain($value) .'"';
            }
            return $t;
        }
    }
    
}