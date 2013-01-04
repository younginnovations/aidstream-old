<?php

class Zend_View_Helper_Length extends Zend_View_Helper_Abstract
{

    function length()
    {
        return $this;

    }

    public function adjustSingleLength($text)
    {

        if (strlen($text) > 40)
        {
            return substr($text , 0 , 40) . '...';
        } else
        {
            return $text;
        }

    }

    public function adjustDoubleLength($text)
    {
        if (strlen($text) > 15)
        {
            return substr($text , 0 , 15) . '...';
        } else
        {
            return $text;
        }

    }

    public function adjustTripleLength($text)
    {
        if (strlen($text) > 15)
        {
            return substr($text , 0 , 10) . '...';
        } else
        {
            return $text;
        }

    }

}
