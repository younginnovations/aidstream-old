<?php

class Iati_Activity_ElementExampleTest extends PHPUnit_Framework_TestCase
{
    public function testElementCreation()
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
    }
    
    public function testAccessElements()
    {
        $activity = new Iati_Activity_Element_Activity();
        $activityAttribs = array(
            'xmlLang' => 'en',
            'defaultCurrency' => 'USD',
            'hierarchy' => '0',
            'lastUpdatedDatetime' => '2011-02-01',
        );
        $activity->setAttribs($activityAttribs);
        // Another technique for adding new element is 
        $reportingOrg = $activity->createElement('reportingOrg');
        $activity->addElement($reportingOrg);
        $activity->addElement('participatingOrg');
        
        // A database layer might want to access all the elements and save it in database
        $actualActivityAttribs = $activity->getAttribs();
        $this->assertEquals($activityAttribs, $actualActivityAttribs);
        
        $childElements = $activity->getElements();
        Zend_Debug::dump($childElements);
        $actualReportingOrg = $childElements[0];
        $this->assertEquals('Iati_Activity_Element_ReportingOrg', get_class($actualReportingOrg));
        
        $actualParticipatingOrg = $childElements[1];
        $this->assertEquals('Iati_Activity_Element_ParticipatingOrg', get_class($actualParticipatingOrg));
    }
}
