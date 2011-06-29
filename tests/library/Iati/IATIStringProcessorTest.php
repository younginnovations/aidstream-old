<?php
class IATIStringProcessorTest extends PHPUnit_Framework_TestCase
{
    public function testStringProcessor()
    {
        
        $xmlString = file_get_contents("http://localhost/iati-activity.xml");
        $iatiActivities = new Iati_StringProcessor($xmlString);
        print_r($iatiActivities);exit();
        $iatiActivities->process();
        exit();
    }
}
