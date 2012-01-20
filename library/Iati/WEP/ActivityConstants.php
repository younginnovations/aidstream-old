<?php
/**
 * Class for storing constants for wep activities;
 */
class Iati_WEP_ActivityConstants
{
    static $displayName = array(
                                'participating_org' => 'Participating Organisation' ,
                                'reporting_org' => 'Reporting Organisation'
                                );
    
    public static function hasDisplayName($element)
    {
        if(self::$displayName[$element]){
            return true;
        }
        return false;
    }
    public static function getDisplayName($element)
    {
        return self::$displayName[$element];
    }
}