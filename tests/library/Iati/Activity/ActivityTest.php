<?php
class ActivityTest extends PHPUnit_Framework_TestCase
{
    public function testReportingOrg()
    {
        //        $participatingOrg = new Iati_IatiElements_ParticipatingOrg();

        $p = '<?xml version="1.0"?>
<iati-activities version="1.01" generated-datetime="2011-02-01">
<iati-activity xml:lang="en" default-currency="USD" hierarchy="0" last-updated-datetime="2011-02-01">
<iati-identifier>GB-1 101-11111</iati-identifier>
<reporting-org ref="GB-1" type="INGO" xml:lang="en">UNDP</reporting-org></iati-activity>
</iati-activities>';

        $xml = new SimpleXMLElement($p);
        $actual = Iati_Activity_ReportingOrg::Process($xml->{'iati-activity'}->{'reporting-org'});

        //        print_r($xml->{'iati-activity'}->{'reporting-org'});
        //        $reporting = $xml->{'iati-activity'}->{'reporting-org'};
        //    $namespaces = $reporting->getNameSpaces(true);
        //    print_r($namespaces);exit();
        /* if($nmespaces){
        $xm = $reporting->children($namespaces['xml']);
        $xm = (array)$xm;
        print_r($xm['@attributes']['lang']);

        }
        exit();*/
        //        print_r($actual);exit();
        $e = new Iati_Activity_ReportingOrg();
        $e->setRef('GB-1');
        $e->setType('INGO');
        $e->setXmlLang('en');
        $e->setText('UNDP');
        $expected = array($e);
        $this->assertEquals($expected, $actual);
    }

    public function testParticpatingOrg()
    {
        //        $participatingOrg = new Iati_IatiElements_ParticipatingOrg();

        $p = '<?xml version="1.0"?>
<iati-activities version="1.01" generated-datetime="2011-02-01">
<iati-activity>
<participating-org role="funding" ref="41114" type="21" xml:lang="en">UNITED NATIONS DEVELOPMENT PROGRAM</participating-org>
<participating-org role="implementing" ref="bilaeral" type="10" xml:lang="en">BKF-National Execution</participating-org></iati-activity></iati-activities>';

        $xml = new SimpleXMLElement($p);
//        print_r($xml);exit();
        $a = $xml->{'iati-activity'}->{'participating-org'};

        $actual = Iati_Activity_ParticipatingOrg::Process($a);
        //        print_r($actual);exit();
        $e = new Iati_Activity_ParticipatingOrg();
        $e->setRef('41114');
        $e->setRole("funding");
        $e->setType('21');
        $e->setXmlLang('en');
        $e->setText('UNITED NATIONS DEVELOPMENT PROGRAM');

        $e1 = new Iati_Activity_ParticipatingOrg();
        $e1->setRef('bilaeral');
        $e1->setRole("implementing");
        $e1->setType('10');
        $e1->setXmlLang('en');
        $e1->setText('BKF-National Execution');

        $expected = array($e, $e1);
        $this->assertEquals($expected, $actual);
    }

    public function testRecipientCountry()
    {
        //        $participatingOrg = new Iati_IatiElements_ParticipatingOrg();

        $p = '<?xml version="1.0"?>
<iati-activities version="1.01" generated-datetime="2011-02-01">
<iati-activity>
<recipient-country code="AF" percentage="2" xml:lang="en">Afghanistan</recipient-country>
<recipient-country code="NP" percentage="21" xml:lang="en">Nepal</recipient-country></iati-activity></iati-activities>';

        $xml = new SimpleXMLElement($p);
        $actual = Iati_Activity_RecipientCountry::Process($xml->{'iati-activity'}->{'recipient-country'});
        //        print_r($actual);exit();
        $e = new Iati_Activity_RecipientCountry();
        $e->setCode('AF');
        $e->setPercentage("2");
        $e->setXmlLang('en');
        $e->setText('Afghanistan');

        $e1 = new Iati_Activity_RecipientCountry();
        $e1->setCode('NP');
        $e1->setPercentage("21");
        $e1->setXmlLang('en');
        $e1->setText('Nepal');

        $expected = array($e, $e1);
        $this->assertEquals($expected, $actual);
    }

    public function testRecipientRegion()
    {
        //        $participatingOrg = new Iati_IatiElements_ParticipatingOrg();

        $p = '<?xml version="1.0"?>
<iati-activities version="1.01" generated-datetime="2011-02-01">
<iati-activity>
<recipient-region code="78" percentage="10" xml:lang="en">Africa, Regional</recipient-region>
<recipient-region code="798" percentage="10" xml:lang="en">Asia, Regional</recipient-region></iati-activity></iati-activities>';

        $xml = new SimpleXMLElement($p);
        
        $actual = Iati_Activity_RecipientRegion::Process($xml->{'iati-activity'}->{'recipient-region'});
        //        print_r($actual);exit();
        $e = new Iati_Activity_RecipientRegion();
        $e->setCode('78');
        $e->setPercentage("10");
        $e->setXmlLang('en');
        $e->setText('Africa, Regional');

        $e1 = new Iati_Activity_RecipientRegion();
        $e1->setCode('798');
        $e1->setPercentage("10");
        $e1->setXmlLang('en');
        $e1->setText('Asia, Regional');

        $expected = array($e, $e1);
        $this->assertEquals($expected, $actual);
    }

    public function testTitle()
    {
        //        $participatingOrg = new Iati_IatiElements_ParticipatingOrg();

        $p = '<?xml version="1.0"?>
<iati-activities version="1.01" generated-datetime="2011-02-01">
<iati-activity>
<title xml:lang="en">Renfocement Capa planification</title>
</iati-activity></iati-activities>';

        $xml = new SimpleXMLElement($p);
      
        $actual = Iati_Activity_Title::Process($xml->{'iati-activity'}->{'title'});
        //        print_r($actual);exit();
        $e = new Iati_Activity_Title();
        $e->setXmlLang('en');
        $e->setText('Renfocement Capa planification');
        $expected = array($e);
        $this->assertEquals($expected, $actual);
    }

    public function testDescription()
    {
        $string = '<?xml version="1.0"?>
<iati-activities version="1.01" generated-datetime="2011-02-01">
<iati-activity>
<description type="general" xml:lang="en">Long Description: Loutput 63252 a pour objectif principal le renforcement des capacités de planification stratégique des 13 régions du Burkina Faso</description>
</iati-activity></iati-activities>';

        $xml = new SimpleXMLElement($string);

        $actual = Iati_Activity_Description::Process($xml->{'iati-activity'}->{'description'});

        $e = new Iati_Activity_Description();
        $e->setType('general');
        $e->setXmlLang('en');
        $e->setText('Long Description: Loutput 63252 a pour objectif principal le renforcement des capacités de planification stratégique des 13 régions du Burkina Faso');

        $expected = array($e);
        $this->assertEquals($expected, $actual);
    }

    public function testActivityWebsite()
    {
        $string = '<?xml version="1.0"?>
<iati-activities version="1.01" generated-datetime="2011-02-01">
<iati-activity><activity-website>http://www.yipl.com.np</activity-website></iati-activity></iati-activities>';
        $xml = new SimpleXMLElement($string);
        $actual = Iati_Activity_ActivityWebsite::Process($xml->{'iati-activity'}->{'activity-website'});
        $e = new Iati_Activity_ActivityWebsite();
        $e->setText('http://www.yipl.com.np');
        
        $expected = array($e);
        $this->assertEquals($expected, $actual);
    }
    
    public function testActivityDate()
    {
        $string = '<?xml version="1.0"?>
<iati-activities version="1.01" generated-datetime="2011-02-01">
<iati-activity><activity-date type="start-planned" iso-date="2011-01-02" xml:lang="en">2011</activity-date><activity-date type="end-planned" iso-date="2011-02-02" xml:lang="en">2011</activity-date>
</iati-activity>
</iati-activities>';
        $xml = new SimpleXMLElement($string);
        //print_r($xml);
        $actual = Iati_Activity_ActivityDate::Process($xml->{'iati-activity'}->{'activity-date'});
        //print_r($actual);
        $e = new Iati_Activity_ActivityDate();
        $e->setType("start-planned");
        $e->setIsoDate("2011-01-02");
        $e->setXmlLang('en');
        $e->setText("2011");
        
        $e1 = new Iati_Activity_ActivityDate();
        $e1->setType("end-planned");
        $e1->setIsoDate("2011-02-02");
        $e1->setXmlLang('en');
        $e1->setText("2011");
//        print_r($e);exit();
        $expected = array($e, $e1);
        $this->assertEquals($expected, $actual);
        
    }
    
    public function testOtherIdentifier()
    {
        $string = '<?xml version="1.0"?>
<iati-activities version="1.01" generated-datetime="2011-02-01">
<iati-activity><other-identifier owner-ref="GB-1" owner-name="DFID">105838-1</other-identifier>
<other-identifier owner-ref="GB-2" owner-name="DFID">105839-1</other-identifier>
</iati-activity>
</iati-activities>';

        $xml = new SimpleXMLElement($string);
        $actual = Iati_Activity_OtherIdentifier::Process($xml->{'iati-activity'}->{'other-identifier'});

        $e = new Iati_Activity_OtherIdentifier();
        $e->setOwnerRef("GB-1");
        $e->setOwnerName("DFID");
        $e->setText("105838-1");
        
        $e1 = new Iati_Activity_OtherIdentifier();
        $e1->setOwnerRef("GB-2");
        $e1->setOwnerName("DFID");
        $e1->setText("105839-1");
        print_r($actual);
        $expected = array($e,$e1);
        $this->assertEquals($expected,$actual);
    }
    
    public function testRelatedActivity()
    {
        $string ='<?xml version="1.0"?>
<iati-activities version="1.01" generated-datetime="2011-02-01">
<iati-activity><related-activity type="1" ref="GB-1-105838" xml:lang="en">Trade Sector Programme</related-activity>
</iati-activity>
</iati-activities>';

        $xml = new SimpleXMLElement($string);
        $actual = Iati_Activity_RelatedActivity::Process($xml->{'iati-activity'}->{'related-activity'});

        $e = new Iati_Activity_RelatedActivity();
        $e->setType("1");
        $e->setRef("GB-1-105838");
        $e->setXmlLang("en");
        $e->setText("Trade Sector Programme");
        
        $expected = array($e);
        $this->assertEquals($expected,$actual);
    }
    
    public function testSector()
    {
        $string = '<?xml version="1.0"?>
<iati-activities version="1.01" generated-datetime="2011-02-01">
<iati-activity><sector vocabulary="WB" code="BC" percentage="32" xml:lang="en">STD control including HIV/AIDS</sector>
<sector vocabulary="WB" code="BC" percentage="32" xml:lang="en">STD control including HIV/AIDS</sector>
</iati-activity>
</iati-activities>';

        $xml = new SimpleXMLElement($string);
        print_r($xml);
        $actual = Iati_Activity_Sector::Process($xml->{'iati-activity'}->{'sector'});
        print_r($actual);
        $e = new Iati_Activity_Sector();
        $e->setVocabulary("WB");
        $e->setCode("BC");
        $e->setPercentage("32");
        $e->setXmlLang("en");
        $e->setText("STD control including HIV/AIDS");
        
        $e1 = new Iati_Activity_Sector();
        $e1->setVocabulary("WB");
        $e1->setCode("BC");
        $e1->setPercentage("32");
        $e1->setXmlLang("en");
        $e1->setText("STD control including HIV/AIDS");
        
        $expected = array($e,$e1);
        $this->assertEquals($expected,$actual);
    }
    
    public function testPolicyMarker()
    {
        $string = '<?xml version="1.0"?>
<iati-activities version="1.01" generated-datetime="2011-02-01">
<iati-activity><policy-marker significance="2" vocabulary="DAC" code="04" xml:lang="en">Developpement du commerce</policy-marker>
<policy-marker significance="2" vocabulary="DAC" code="04" xml:lang="en">Developpement du commerce</policy-marker>
</iati-activity>
</iati-activities>';

    $xml = new SimpleXMLElement($string);
    $actual = Iati_Activity_PolicyMarker::Process($xml->{'iati-activity'}->{'policy-marker'});
    
    $e = new Iati_Activity_PolicyMarker();
    $e->setSignificance("2");
    $e->setVocabulary("DAC");
    $e->setCode("04");
    $e->setXmlLang("en");
    $e->setText("Developpement du commerce");
    
    $e1 = new Iati_Activity_PolicyMarker();
    $e1->setSignificance("2");
    $e1->setVocabulary("DAC");
    $e1->setCode("04");
    $e1->setXmlLang("en");
    $e1->setText("Developpement du commerce");
    
    $expected = array($e,$e1);
    $this->assertEquals($expected,$actual);
    }
    
    
    public function testCollaborationType()
    {
    $string = '<?xml version="1.0"?>
<iati-activities version="1.01" generated-datetime="2011-02-01">
<iati-activity><collaboration-type code="1" xml:lang="en">Bilateral</collaboration-type>
</iati-activity>
</iati-activities>';

    $xml = new SimpleXMLElement($string);
    $actual = Iati_Activity_CollaborationType::Process($xml->{'iati-activity'}->{'collaboration-type'});

    $e = new Iati_Activity_CollaborationType();
    $e->setCode("1");
    $e->setXmlLang("en");
    $e->setText("Bilateral");
    
    $expected = array($e);
    $this->assertEquals($expected,$actual);
    }
    
    public function testDefaultFlowType()
    {
    $string = '<?xml version="1.0"?>
<iati-activities version="1.01" generated-datetime="2011-02-01">
<iati-activity><default-flow-type code="10" xml:lang="en">APD</default-flow-type>
</iati-activity>
</iati-activities>';

    $xml = new SimpleXMLElement($string);
    $actual = Iati_Activity_DefaultFlowType::Process($xml->{'iati-activity'}->{'default-flow-type'});

    $e = new Iati_Activity_DefaultFlowType();
    $e->setCode("10");
    $e->setXmlLang("en");
    $e->setText("APD");
    
    $expected = array($e);
    $this->assertEquals($expected,$actual);
    }
    
    public function testDefaultAidType()
    {
    $string = '<?xml version="1.0"?>
<iati-activities version="1.01" generated-datetime="2011-02-01">
<iati-activity><default-aid-type code="A02" xml:lang="">Soutien budgetaire sectoriel</default-aid-type>
</iati-activity>
</iati-activities>';

    $xml = new SimpleXMLElement($string);
    $actual = Iati_Activity_DefaultAidType::Process($xml->{'iati-activity'}->{'default-aid-type'});
    
    $e = new Iati_Activity_DefaultAidType();
    $e->setCode("A02");
    $e->setXmlLang("fr");
    $e->setText("Soutien budgetaire sectoriel");
    
    $expected = array($e);
    $this->assertEquals($expected,$actual);
    }
    
    public function testDefaultFinanceType()
    {
    $string = '<?xml version="1.0"?>
<iati-activities version="1.01" generated-datetime="2011-02-01">
<iati-activity><default-finance-type code="100" xml:lang="fr">Don</default-finance-type>
</iati-activity>
</iati-activities>';

    $xml = new SimpleXMLElement($string);
    $actual = Iati_Activity_DefaultFinanceType::Process($xml->{'iati-activity'}->{'default-finance-type'});

    $e = new Iati_Activity_DefaultFinanceType();
    $e->setCode("100");
    $e->setXmlLang("fr");
    $e->setText("Don");
    
    $expected = array($e);
    $this->assertEquals($expected,$actual);
    }
    
    public function testDefaultTiedStatus()
    {
    $string = '<?xml version="1.0"?>
<iati-activities version="1.01" generated-datetime="2011-02-01">
<iati-activity><default-tied-status code="5" xml:lang="en">Aide non-liee</default-tied-status>
</iati-activity>
</iati-activities>';

    $xml = new SimpleXMLElement($string);
    $actual = Iati_Activity_DefaultTiedStatus::Process($xml->{'iati-activity'}->{'default-tied-status'});
    
    $e = new Iati_Activity_DefaultTiedStatus();
    $e->setCode("5");
    $e->setXmlLang("en");
    $e->setText("Aide non-liee");
    
    $expected = array($e);
    $this->assertEquals($expected,$actual);
    }
        public function testContactInfo()
    {
        $string = '<?xml version="1.0"?>
<iati-activities version="1.01" generated-datetime="2011-02-01">
<iati-activity><contact-info>
<organisation>DFID</organisation>
<person-name>Joe Brown</person-name>
<telephone>+442071239876</telephone>
<telephone>3333333333</telephone>
<email>jbrown@dfid.gov</email>
<mailing-address>1 Palace Street, London SW1E 5HE</mailing-address>
</contact-info></iati-activity>
</iati-activities>';
        
        $xml = new SimpleXMLElement($string);
        $actual = Iati_Activity_ContactInfo::Process($xml->xpath('iati-activity/contact-info'));
        $e = new Iati_Activity_ContactInfo();
        $e->setEmail('jbrown@dfid.gov');
        $e->setMailingAddress('1 Palace Street, London SW1E 5HE');
        $e->setOrganisation('DFID');
        $e->setPersonName('Joe Brown');
        $e->setTelephone(array('+442071239876','3333333333'));
        
        $expected  = array($e);
        $this->assertEquals($expected, $actual);
    }
    
    public function testTransaction()
    {
        $string = '<?xml version="1.0"?>
<iati-activities version="1.01" generated-datetime="2011-02-01">
<iati-activity><transaction>
   <transaction-type code="C">Commitment</transaction-type>
   <provider-org ref="GB-1" provider-activity-id="GB-1-10538">DFID</provider-org>
   <receiver-org ref="CG-3" receiver-activity-id="CG-3-1440">Ministere du Plan, RDC</receiver-org>
   <value currency="GBP" value-date="2010-05-27">25000</value>
   <description xml:lang="en">Less disbursed than budgeted due to targets not being met</description>
   <transaction-date iso-date="2010-05-27">2010</transaction-date>
   <flow-type code="10" xml:lang="en">APD</flow-type>
   <aid-type code="A02" xml:lang="en">Sector Budget Support</aid-type>
   <finance-type code="100" xml:lang="fr">Don</finance-type>
   <tied-status code="5" xml:lang="en">Untied</tied-status>
   <disbursement-channel code="1">Cash to treasury</disbursement-channel>
</transaction>
<transaction>
   <transaction-type code="C">Commitment</transaction-type>
   <provider-org ref="GB-1" provider-activity-id="GB-1-10538">DFID</provider-org>
   <receiver-org ref="CG-3" receiver-activity-id="CG-3-1440">Ministere du Plan, RDC</receiver-org>
   <value currency="GBP" value-date="2010-05-27">25000</value>
   <description xml:lang="en">Less disbursed than budgeted due to targets not being met</description>
   <transaction-date iso-date="2010-05-27">2010</transaction-date>
   <flow-type code="10" xml:lang="en">APD</flow-type>
   <aid-type code="A02" xml:lang="en">Sector Budget Support</aid-type>
   <finance-type code="100" xml:lang="fr">Don</finance-type>
   <tied-status code="5" xml:lang="en">Untied</tied-status>
   <disbursement-channel code="1">Cash to treasury</disbursement-channel>
</transaction></iati-activity>
</iati-activities>';
        
        $xml = new SimpleXMLElement($string);
        $actual = Iati_Activity_Transaction::Process($xml->xpath('iati-activity/transaction'));
        print_r($actual);exit();
        $e = new Iati_Activity_Transaction();
        $e->setTransactionType();
        $e->setProviderOrg();
        $e->setRecivierOrg();
        $e->setValue();
        $e->setDescription();
        $e->setTransactionDate();
        $e->setFlowType();
        $e->setAidType();
        $e->setFinanceType();
        $e->setTiedStatus();
        $e->setDisbursementChannel();
        
        
    }
    
    public function testDocumentLink()
    {
        $string = '<?xml version="1.0"?>
<iati-activities version="1.01" generated-datetime="2011-02-01">
<iati-activity><document-link url="http://reporting.org/activities/documents/xx546.doc" format="application/msword" xml:lang="en">
   <language>en</language>
   <category code="A04">Conditions</category>
   <title>Project Conditions</title>
</document-link></iati-activity>
</iati-activities>';
        $xml = new SimpleXMLElement($string);
        $actual = Iati_Activity_DocumentLink::Process($xml->xpath('iati-activity/document-link'));
        print_r($actual);exit();
        
    }
    
 /*   public function testLocation()
    {
        $string = '<?xml version="1.0"?>
<iati-activities version="1.01" generated-datetime="2011-02-01">
<iati-activity><location percentage="23">
   <location-type code="PPL"/>
   <name xml:lang="en">Buwenge</name>
   <description>Buwenge hospital</description>
   <administrative country="AF" adm1="KAN" adm2="KAN">Kandahar Province, Afghanistan</administrative>
   <coordinates latitude="0.651922" longitude="33.16983" precision="1"/>
   <gazetteer-entry gazetteer-ref="geonames.org">167483</gazetteer-entry>
</location></iati-activity>
</iati-activities>';
        
        $xml = new SimpleXMLElement($string);
        $actual = Iati_Activity_Location::Process($xml->xpath('iati-activity/location'));
        print_r($actual);exit();
    }*/
}


