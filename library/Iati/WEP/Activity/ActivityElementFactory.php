<?php
class Iati_WEP_Activity_ActivityElementFactory
{
    //    public function __construct()

    public static function factory($type)
    {
        $classname = 'Iati_WEP_Activity_' . $type;
//        $classObj =  new $classname();
        if (class_exists($classname)) {
            return new $classname();
        }
        else {
            throw new Exception('Driver not found');
        }
    }
}