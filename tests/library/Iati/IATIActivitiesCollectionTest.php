<?php
class IATIActivitiesCollectionTest extends PHPUnit_Framework_TestCase
{
    public function testProcess()
    {
        $iatiActivities = new Iati_ActivitiesCollection();
        $iatiActivities->setXmlPath("http://localhost/iati-activity.xml");
//        $iatiActivities->setXmlPath("file:///var/www/iati-activity.xml");
        $activities = $iatiActivities->process();
        print_r($activities);
        exit();
    }
}