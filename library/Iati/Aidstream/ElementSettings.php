<?php
/**
 * Use this class to store required settings/defaults for aidstream elements
 */
class Iati_Aidstream_ElementSettings
{
    /*
        These elements added/edited one at at time
    */
    static $individuallyHandledElement = array('Transaction' , 'Result' , 'CountryBudgetItems');
    
    public static function isHandledIndividually($classname)
    {
        if(in_array($classname , self::$individuallyHandledElement)){
            return true;
        } else {
            return false;
        }
    }
}