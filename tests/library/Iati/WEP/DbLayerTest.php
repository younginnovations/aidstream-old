<?php

class Iati_WEP_DbLayerTest extends PHPUnit_Framework_TestCase
{
	public function testSaveElement()
	{
		$activities = new Iati_Activity_Element_ActivityCollection();
        $activity = $activities->addElement('activity');
        $activity->setAttrib('xmlLang', 'en');
        $activity->setAttrib('defaultCurrency', 'USD');
        $activity->setAttribs(array(
            'hierarchy' => '0',
            'lastUpdatedDatetime' => '2011-02-01',
        ));
        $iatiIdentifier = $activity->addElement('iatiIdentifier');
        // fill up properties of $iatiIdentifier
        
        // Another technique for adding new element is 
        $reportingOrg = $activity->createElement('reportingOrg');
        $reportingOrg->setAttribs(array(
            'ref' => "GB-1",
            'type' => "INGO",
            'xmlLang' => "en",
        ));
        $activity->addElement($reportingOrg);
        
        $dbWrapper = new Iati_WEP_Activity_DbWrapper($activity);
        Zend_Debug::dump($dbWrapper);
        $dbLayer = new Iati_WEP_DbLayer();
//        $dbLayer->save($dbWrapper);
	}
}