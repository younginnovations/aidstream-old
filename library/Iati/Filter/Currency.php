<?php

class Iati_Filter_Currency implements Zend_Filter_Interface
{
    public function filter($value)
    {
        
        $amount = explode('.',$value);
        if($amount[0]){
            $filter = new Zend_Filter_Digits();
            $filteredAmount = $filter->filter($amount[0]);
        }
        if($amount[1]){
            $filteredDecimals = $filter->filter($amount[1]);
            $filteredAmount = $filteredAmount.".".$filteredDecimals;
            $filteredAmount = round((float)$filteredAmount , 2);
        }
        return $filteredAmount;
        //return number_format((float)$filteredAmount, 2, '.', '');;
    }
}
