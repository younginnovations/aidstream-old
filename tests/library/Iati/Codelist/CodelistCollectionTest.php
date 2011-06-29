<?php
class Iati_Codelist_CodelistCollectionTest extends PHPUnit_Framework_TestCase
{
    public function testCode(){
        
$filenames = array('TiedStatus.php', 'Sector.php', 'PublisherType.php', 'OrganisationType.php', 'BilateralAidAgencyCodes.php', 'PolicyMarker.php', 'CollaborationType.php', 'Region.php', 'FinanceType.php', 'GeographicalPrecision.php', 'DescriptionType.php', 'AdministrativeArea1.php', 'OrganisationRole.php', 'FlowType.php', 'AdministrativeArea2.php', 'IndicatorMeasure.php', 'DocumentCategory.php', 'BudgetType.php', 'ResultType.php', 'ActivityStatus.php', 'AidType.php', 'TransactionType.php', 'Vocabulary.php', 'FileFormat.php', 'VerificationStatus.php', 'Country.php', 'PolicySignificance.php', 'OrganisationIdentifierMultilateral.php', 'RelatedActivityType.php', 'Currency.php', 'ActivityDateType.php', 'GazetteerAgency.php', 'Language.php', 'LocationType.php', 'DisbursementChannel.php', 'OrganisationIdentifierIngo.php', 'ConditionType.php', );

foreach($filenames as $value){
    $createFile = fopen('/home/manisha/Documents/collection/'.$file, 'w') or die("can't open file");
$class = str_replace(".php", "", $value);
        $content = 
"<?php
class Iati_Codelist_Collection_$class extends Iati_Codelist_Collection_ICodelistCollection
{
    function __construct(" . '$lang = '."'en') {".
        '$this'."->tableName = Iati_Codelist_Constants::$" . "$class;".
        '$this'."->lang = ". '$lang;'.
        '$this'."->fetchResultSet();".
        '$this'."->Process();" .
    "}

}";
    fclose($createFile);
    
    file_put_content('/home/manisha/Documents/collection/'.$file, $content);

}

exit();
    }
    
    public function testCountries()
    {
        
        /*$a = Iati_Codelist_Collection_Country::Process('1');
        var_dump($a);exit();*/
        
        $countryCollection = new Iati_Codelist_Collection_Country('1');
        $result = $countryCollection->getResult();
        var_dump($result);
        die();
        
        
        
        
        
        $data = array(
        //                'data' => array(
                    '0' => array(
                            'code' =>'AF',
                            'name' => 'AFGHANISTAN',
        ),
                    '1' => array(
                            'code' =>'NP',
                            'name' => 'NEPAL',
        ),
        //                ),
        //                'error' =>array(),

        );
        
       

        $data = json_encode($data);
        //        $data = json_decode($data);
        //        print_r($data->data);exit();
        //        $countries = new YIPL_CodelistCollection_CountriesCollection();

        $actual = Iati_Codelist_Collection_Country::Process('1');
        print_r($actual);exit();

        /*$e = new YIPL_CodelistCollection_Objects_Country();
         $e->setCode("AF");
         $e->setName("AFGHANISTAN");

         $e1 = new YIPL_CodelistCollection_Objects_Country();
         $e1->setCode("NP");
         $e1->setName("NEPAL");*/

        $data1 = array(
                'codelist' => array(
                    'country' => array(
                        '0' => array(
                                'code' =>'AF',
                                'name' => 'AFGHANISTAN',
        ),
                        '1' => array(
                                'code' =>'NP',
                                'name' => 'NEPAL',
        ),
        ),
        ),
        //                'error' =>array(),

        );
        $expected = json_encode($data1);

        $this->assertEquals($expected, json_encode($actual));
    }

    /*public function testCurrency()
     {
     $data = array(
     'data' => array(
     '0' => array(
     'code' =>'AF',
     'name' => 'AFGHANISTAN',
     ),
     '1' => array(
     'code' =>'NP',
     'name' => 'NEPAL',
     ),
     ),
     'error' =>array(),

     );

     $data = json_encode($data);
     //        $data = json_decode($data);
     //        print_r($data->data);exit();
     //        $countries = new YIPL_CodelistCollection_CountriesCollection();

     $actual = YIPL_CodelistCollection_CountriesCollection::Process($data);
     print_r(json_encode($actual));

     $e = new YIPL_CodelistCollection_Objects_Country();
     $e->setCode("AF");
     $e->setName("AFGHANISTAN");

     $e1 = new YIPL_CodelistCollection_Objects_Country();
     $e1->setCode("NP");
     $e1->setName("NEPAL");

     $expected = array($e, $e1);

     $this->assertEquals($expected, $actual);
     }*/

    public function testActivityDateType()
    {
        $data = array(
        //                'data' => array(
                       '0' => array(
                                'code' =>'start-planned',
                                'name' => 'The planned start date as recorded in project documentation. Can be date of signature of activity agreement or approval by relevant body',
        ),
                        '1' => array(
                                'code' =>'end-planned',
                                'name' => 'The planned end date as recorded in project documentation',
        ),
        //                ),
        //                'error' =>array(),

        );

        $data = json_encode($data);
        //        $data = json_decode($data);
        //        print_r($data->data);exit();
        //        $countries = new YIPL_CodelistCollection_CountriesCollection();

        $actual = YIPL_CodelistCollection_ActivityDateTypeCollection::Process($data);
        print_r(json_encode($actual));

        /*$e = new YIPL_CodelistCollection_Objects_Country();
         $e->setCode("AF");
         $e->setName("AFGHANISTAN");

         $e1 = new YIPL_CodelistCollection_Objects_Country();
         $e1->setCode("NP");
         $e1->setName("NEPAL");*/

        $data1 = array(
                'codelist' => array(
                    'activity-date-type' => array(
                        '0' => array(
                                'code' =>'start-planned',
                                'name' => 'The planned start date as recorded in project documentation. Can be date of signature of activity agreement or approval by relevant body',
        ),
                        '1' => array(
                                'code' =>'end-planned',
                                'name' => 'The planned end date as recorded in project documentation',
        ),
        ),
        ),
        //                'error' =>array(),

        );
        $expected = json_encode($data1);

        $this->assertEquals($expected, json_encode($actual));
    }

    public function testActivityStatus()
    {
        $data = array(
        //                'data' => array(
                       '0' => array(
                                'code' =>'1',
                                'description' => 'Pipeline/identification',
        ),
                        '1' => array(
                                'code' =>'2',
                                'description' => 'Implementation',
        ),
        //                ),
        //                'error' =>array(),

        );

        $data = json_encode($data);
        //        $data = json_decode($data);
        //        print_r($data->data);exit();
        //        $countries = new YIPL_CodelistCollection_CountriesCollection();

        $actual = YIPL_CodelistCollection_ActivityStatusCollection::Process($data);
        print_r(json_encode($actual));

        /*$e = new YIPL_CodelistCollection_Objects_Country();
         $e->setCode("AF");
         $e->setName("AFGHANISTAN");

         $e1 = new YIPL_CodelistCollection_Objects_Country();
         $e1->setCode("NP");
         $e1->setName("NEPAL");*/

        $data1 = array(
                'codelist' => array(
                    'activity-status' => array(
                       '0' => array(
                                'code' =>'1',
                                'description' => 'Pipeline/identification',
        ),
                        '1' => array(
                                'code' =>'2',
                                'description' => 'Implementation',
        ),
        ),
        ),
        //                'error' =>array(),

        );
        $expected = json_encode($data1);

        $this->assertEquals($expected, json_encode($actual));
    }

    public function testCollaborationType()
    {
        $data = array(
        //                'data' => array(
                       '0' => array(
                                'code' =>'1',
                                'name' => 'Bilateral',
                                'description' => 'Pipeline/identification',
        ),
        //                ),
        //                'error' =>array(),

        );

        $data = json_encode($data);
        //        $data = json_decode($data);
        //        print_r($data->data);exit();
        //        $countries = new YIPL_CodelistCollection_CountriesCollection();

        $actual = YIPL_CodelistCollection_CollaborationTypeCollection::Process($data);
        print_r(json_encode($actual));

        /*$e = new YIPL_CodelistCollection_Objects_Country();
         $e->setCode("AF");
         $e->setName("AFGHANISTAN");

         $e1 = new YIPL_CodelistCollection_Objects_Country();
         $e1->setCode("NP");
         $e1->setName("NEPAL");*/

        $data1 = array(
                'codelist' => array(
                    'collaboration-type' => array(
                       '0' => array(
                                'code' =>'1',
                                'name' => 'Bilateral',
                                'description' => 'Pipeline/identification',
        ),
        ),
        ),
        //                'error' =>array(),

        );
        $expected = json_encode($data1);

        $this->assertEquals($expected, json_encode($actual));
    }

    public function testDescriptionType()
    {
        $data = array(
        //                'data' => array(
                       '0' => array(
                                'code' =>'General',
                                'description' => 'Long description of the activity with no particular structure',
        ),
        //                ),
        //                'error' =>array(),

        );

        $data = json_encode($data);
        //        $data = json_decode($data);
        //        print_r($data->data);exit();
        //        $countries = new YIPL_CodelistCollection_CountriesCollection();

        $actual = YIPL_CodelistCollection_DescriptionTypeCollection::Process($data);
        print_r(json_encode($actual));

        /*$e = new YIPL_CodelistCollection_Objects_Country();
         $e->setCode("AF");
         $e->setName("AFGHANISTAN");

         $e1 = new YIPL_CodelistCollection_Objects_Country();
         $e1->setCode("NP");
         $e1->setName("NEPAL");*/

        $data1 = array(
                'codelist' => array(
                    'description-type' => array(
                       '0' => array(
                                'code' =>'General',
                                'description' => 'Long description of the activity with no particular structure',
        ),
        ),
        ),
        //                'error' =>array(),

        );
        $expected = json_encode($data1);

        $this->assertEquals($expected, json_encode($actual));
    }

    public function testDisbursementChannel()
    {
        $data = array(
        //                'data' => array(
                       '0' => array(
                                'code' =>'1',
                                'description' => 'Money is disbursed through central Ministry of Finance or Treasury',
        ),
                       '1' => array(
                                'code' =>'2',
                                'description' => 'Money is disbursed directly to the implementing institution and managed through a separate bank account',
        ),
        //                ),
        //                'error' =>array(),

        );

        $data = json_encode($data);
        //        $data = json_decode($data);
        //        print_r($data->data);exit();
        //        $countries = new YIPL_CodelistCollection_CountriesCollection();

        $actual = YIPL_CodelistCollection_DisbursementChannelCollection::Process($data);
        print_r(json_encode($actual));

        /*$e = new YIPL_CodelistCollection_Objects_Country();
         $e->setCode("AF");
         $e->setName("AFGHANISTAN");

         $e1 = new YIPL_CodelistCollection_Objects_Country();
         $e1->setCode("NP");
         $e1->setName("NEPAL");*/

        $data1 = array(
                'codelist' => array(
                    'disbursement-channel' => array(
                       '0' => array(
                                'code' =>'1',
                                'description' => 'Money is disbursed through central Ministry of Finance or Treasury',
        ),
                       '1' => array(
                                'code' =>'2',
                                'description' => 'Money is disbursed directly to the implementing institution and managed through a separate bank account',
        ),
        ),
        ),
        //                'error' =>array(),

        );
        $expected = json_encode($data1);

        $this->assertEquals($expected, json_encode($actual));
    }

    public function testFileFormat()
    {
        $data = array(
        //                'data' => array(
                       '0' => array(
                                'code' =>'csv',
                                'description' => 'Comma Separated Values',
        ),
                       '1' => array(
                                'code' =>'html',
                                'description' => 'Hypertext Markup Language',
        ),
        //                ),
        //                'error' =>array(),

        );

        $data = json_encode($data);
        //        $data = json_decode($data);
        //        print_r($data->data);exit();
        //        $countries = new YIPL_CodelistCollection_CountriesCollection();

        $actual = YIPL_CodelistCollection_FileFormatCollection::Process($data);
        print_r(json_encode($actual));

        /*$e = new YIPL_CodelistCollection_Objects_Country();
         $e->setCode("AF");
         $e->setName("AFGHANISTAN");

         $e1 = new YIPL_CodelistCollection_Objects_Country();
         $e1->setCode("NP");
         $e1->setName("NEPAL");*/

        $data1 = array(
                'codelist' => array(
                    'file-format' => array(
                       '0' => array(
                                'code' =>'csv',
                                'description' => 'Comma Separated Values',
        ),
                       '1' => array(
                                'code' =>'html',
                                'description' => 'Hypertext Markup Language',
        ),
        ),
        ),
        //                'error' =>array(),

        );
        $expected = json_encode($data1);

        $this->assertEquals($expected, json_encode($actual));
    }

    public function testFinanceType()
    {
        $data = array(
        //                'data' => array(
                       '0' => array(
                                'code' =>'100',
                                'description' => 'GRANT Transfers in cash or in kind for which no legal debt is incurred by the recipient.',
        ),
                       '1' => array(
                                'code' =>'110',
                                'description' => 'Aid grant excluding debt reorganisation',
        ),
        //                ),
        //                'error' =>array(),

        );

        $data = json_encode($data);
        //        $data = json_decode($data);
        //        print_r($data->data);exit();
        //        $countries = new YIPL_CodelistCollection_CountriesCollection();

        $actual = YIPL_CodelistCollection_FinanceTypeCollection::Process($data);
        print_r(json_encode($actual));

        /*$e = new YIPL_CodelistCollection_Objects_Country();
         $e->setCode("AF");
         $e->setName("AFGHANISTAN");

         $e1 = new YIPL_CodelistCollection_Objects_Country();
         $e1->setCode("NP");
         $e1->setName("NEPAL");*/

        $data1 = array(
                'codelist' => array(
                    'finance-type' => array(
                       '0' => array(
                                'code' =>'100',
                                'description' => 'GRANT Transfers in cash or in kind for which no legal debt is incurred by the recipient.',
        ),
                       '1' => array(
                                'code' =>'110',
                                'description' => 'Aid grant excluding debt reorganisation',
        ),
        ),
        ),
        //                'error' =>array(),

        );
        $expected = json_encode($data1);

        $this->assertEquals($expected, json_encode($actual));
    }

    public function testFlowType()
    {
        $data = array(
        //                'data' => array(
                       '0' => array(
                                'code' =>'100',
                                'name' => 'Flow1',
                                'description' => 'GRANT Transfers in cash or in kind for which no legal debt is incurred by the recipient.',
        ),
                       '1' => array(
                                'code' =>'110',
                                'name' =>'Flow2',
                                'description' => 'Aid grant excluding debt reorganisation',
        ),
        //                ),
        //                'error' =>array(),

        );

        $data = json_encode($data);
        //        $data = json_decode($data);
        //        print_r($data->data);exit();
        //        $countries = new YIPL_CodelistCollection_CountriesCollection();

        $actual = YIPL_CodelistCollection_FlowTypeCollection::Process($data);
        print_r(json_encode($actual));

        /*$e = new YIPL_CodelistCollection_Objects_Country();
         $e->setCode("AF");
         $e->setName("AFGHANISTAN");

         $e1 = new YIPL_CodelistCollection_Objects_Country();
         $e1->setCode("NP");
         $e1->setName("NEPAL");*/

        $data1 = array(
                'codelist' => array(
                    'flow-type' => array(
                       '0' => array(
                                'code' =>'100',
                                'name' => 'Flow1',
                                'description' => 'GRANT Transfers in cash or in kind for which no legal debt is incurred by the recipient.',
        ),
                       '1' => array(
                                'code' =>'110',
                                'name' =>'Flow2',
                                'description' => 'Aid grant excluding debt reorganisation',
        ),
        ),
        ),
        //                'error' =>array(),

        );
        $expected = json_encode($data1);

        $this->assertEquals($expected, json_encode($actual));
    }

    public function testGazetteerAgency()
    {
        $data = array(
        //                'data' => array(
                       '0' => array(
                                'code' =>'NGA',
                                'description' => 'National Geospatial Intelligence Agency',
        ),
                       '1' => array(
                                'code' =>'GEO',
                                'description' => 'Geonames.org',
        ),
        //                ),
        //                'error' =>array(),

        );

        $data = json_encode($data);
        //        $data = json_decode($data);
        //        print_r($data->data);exit();
        //        $countries = new YIPL_CodelistCollection_CountriesCollection();

        $actual = YIPL_CodelistCollection_GazetteerAgencyCollection::Process($data);
        print_r(json_encode($actual));

        /*$e = new YIPL_CodelistCollection_Objects_Country();
         $e->setCode("AF");
         $e->setName("AFGHANISTAN");

         $e1 = new YIPL_CodelistCollection_Objects_Country();
         $e1->setCode("NP");
         $e1->setName("NEPAL");*/

        $data1 = array(
                'codelist' => array(
                    'gazetteer-agency' => array(
                       '0' => array(
                                'code' =>'NGA',
                                'description' => 'National Geospatial Intelligence Agency',
        ),
                       '1' => array(
                                'code' =>'GEO',
                                'description' => 'Geonames.org',
        ),
        ),
        ),
        //                'error' =>array(),

        );
        $expected = json_encode($data1);

        $this->assertEquals($expected, json_encode($actual));
    }

    public function testGeographicalPrecision()
    {
        $data = array(
        //                'data' => array(
                       '0' => array(
                                'code' =>'1',
                                'description' => 'The coordinates corresponds to an exact location, such as a populated place or a hill. The code is also used for locations that join a location which is a line (such as a road or railroad). Lines are not coded only the points that connect lines. All points that are mentioned in the source are coded.',
        ),
                       '1' => array(
                                'code' =>'2',
                                'description' => 'The location is mentioned in the source as being ﾓnearﾔ, in the ﾓareaﾔ of, or up to 25 km away from an exact location. The coordinates refer to that adjacent, exact, location.',
        ),
        //                ),
        //                'error' =>array(),

        );

        $data = json_encode($data);
        //        $data = json_decode($data);
        //        print_r($data->data);exit();
        //        $countries = new YIPL_CodelistCollection_CountriesCollection();

        $actual = YIPL_CodelistCollection_GeographicalPrecisionCollection::Process($data);
        print_r(json_encode($actual));

        /*$e = new YIPL_CodelistCollection_Objects_Country();
         $e->setCode("AF");
         $e->setName("AFGHANISTAN");

         $e1 = new YIPL_CodelistCollection_Objects_Country();
         $e1->setCode("NP");
         $e1->setName("NEPAL");*/

        $data1 = array(
                'codelist' => array(
                    'geographical-precision' => array(
                       '0' => array(
                                'code' =>'1',
                                'description' => 'The coordinates corresponds to an exact location, such as a populated place or a hill. The code is also used for locations that join a location which is a line (such as a road or railroad). Lines are not coded only the points that connect lines. All points that are mentioned in the source are coded.',
        ),
                       '1' => array(
                                'code' =>'2',
                                'description' => 'The location is mentioned in the source as being ﾓnearﾔ, in the ﾓareaﾔ of, or up to 25 km away from an exact location. The coordinates refer to that adjacent, exact, location.',
        ),
        ),
        ),
        //                'error' =>array(),

        );
        $expected = json_encode($data1);

        $this->assertEquals($expected, json_encode($actual));
    }

    public function testLanguage()
    {
        $data = array(
        //                'data' => array(
                       '0' => array(
                                'code' =>'aa',
                                'language' => 'Afar',
        ),
                       '1' => array(
                                'code' =>'ab',
                                'language' => 'Abkhazian',
        ),
        //                ),
        //                'error' =>array(),

        );

        $data = json_encode($data);
        //        $data = json_decode($data);
        //        print_r($data->data);exit();
        //        $countries = new YIPL_CodelistCollection_CountriesCollection();

        $actual = YIPL_CodelistCollection_LanguageCollection::Process($data);
        print_r(json_encode($actual));

        /*$e = new YIPL_CodelistCollection_Objects_Country();
         $e->setCode("AF");
         $e->setName("AFGHANISTAN");

         $e1 = new YIPL_CodelistCollection_Objects_Country();
         $e1->setCode("NP");
         $e1->setName("NEPAL");*/

        $data1 = array(
                'codelist' => array(
                    'language' => array(
                       '0' => array(
                                'code' =>'aa',
                                'language' => 'Afar',
        ),
                       '1' => array(
                                'code' =>'ab',
                                'language' => 'Abkhazian',
        ),
        ),
        ),
        //                'error' =>array(),

        );
        $expected = json_encode($data1);

        $this->assertEquals($expected, json_encode($actual));
    }

    public function testLocationType()
    {
        $data = array(
        //                'data' => array(
                       '0' => array(
                                'code' =>'PCL',
                                'description' => 'political entity (i.e. a country)',
        ),
                       '1' => array(
                                'code' =>'ADM1',
                                'description' => 'first-order administrative division',
        ),
        //                ),
        //                'error' =>array(),

        );

        $data = json_encode($data);
        //        $data = json_decode($data);
        //        print_r($data->data);exit();
        //        $countries = new YIPL_CodelistCollection_CountriesCollection();

        $actual = YIPL_CodelistCollection_LocationTypeCollection::Process($data);
        print_r(json_encode($actual));

        /*$e = new YIPL_CodelistCollection_Objects_Country();
         $e->setCode("AF");
         $e->setName("AFGHANISTAN");

         $e1 = new YIPL_CodelistCollection_Objects_Country();
         $e1->setCode("NP");
         $e1->setName("NEPAL");*/

        $data1 = array(
                'codelist' => array(
                    'location-type' => array(
                       '0' => array(
                                'code' =>'PCL',
                                'description' => 'political entity (i.e. a country)',
        ),
                       '1' => array(
                                'code' =>'ADM1',
                                'description' => 'first-order administrative division',
        ),
        ),
        ),
        //                'error' =>array(),

        );
        $expected = json_encode($data1);

        $this->assertEquals($expected, json_encode($actual));
    }

    public function testAidType()
    {
        $data = array(
        //                'data' => array(
                       '0' => array(
                                'code' =>'A00',
                                'name' => 'Budget support',
                                'description' => 'For contributions under this category, the donor relinquishes the exclusive control of its funds by sharing the responsibility with the recipient.',
                                'cat' => 'A',
        ),
                       '1' => array(
                                'code' =>'A01',
                                'name' => 'General budget support',
                                'description' => 'Unearmarked contributions to the government budget including funding to support the implementation of macroeconomic reforms (structural adjustment programmes, poverty reduction strategies). Budget support is a method of financing a recipient country’s budget through a transfer of resources from an external financing agency to the recipient government’s national treasury. The funds thus transferred are managed in accordance with the recipient’s budgetary procedures. Funds transferred to the national treasury for financing programmes or projects managed according to different budgetary procedures from those of the recipient country, with the intention of earmarking the resources for specific uses, are therefore excluded.',
                                'cat' => 'A',
        ),
        //                ),
        //                'error' =>array(),

        );

        $data = json_encode($data);
        //        $data = json_decode($data);
        //        print_r($data->data);exit();
        //        $countries = new YIPL_CodelistCollection_CountriesCollection();

        $actual = YIPL_CodelistCollection_AidTypeCollection::Process($data);
        print_r(json_encode($actual));

        /*$e = new YIPL_CodelistCollection_Objects_Country();
         $e->setCode("AF");
         $e->setName("AFGHANISTAN");

         $e1 = new YIPL_CodelistCollection_Objects_Country();
         $e1->setCode("NP");
         $e1->setName("NEPAL");*/

        $data1 = array(
                'codelist' => array(
                    'aid-type' => array(
                       '0' => array(
                                'code' =>'A00',
                                'name' => 'Budget support',
                                'description' => 'For contributions under this category, the donor relinquishes the exclusive control of its funds by sharing the responsibility with the recipient.',
                                'cat' => 'A',
        ),
                       '1' => array(
                                'code' =>'A01',
                                'name' => 'General budget support',
                                'description' => 'Unearmarked contributions to the government budget including funding to support the implementation of macroeconomic reforms (structural adjustment programmes, poverty reduction strategies). Budget support is a method of financing a recipient country’s budget through a transfer of resources from an external financing agency to the recipient government’s national treasury. The funds thus transferred are managed in accordance with the recipient’s budgetary procedures. Funds transferred to the national treasury for financing programmes or projects managed according to different budgetary procedures from those of the recipient country, with the intention of earmarking the resources for specific uses, are therefore excluded.',
                                'cat' => 'A',
        ),
        ),
        ),
        //                'error' =>array(),

        );
        $expected = json_encode($data1);

        $this->assertEquals($expected, json_encode($actual));
    }

    public function testBilateralAidAgencyCodes()
    {
        $data = array(
        //                'data' => array(
                       '0' => array(
                                'code' =>'AT-1',
                                'country' => 'Australia',
                                'abbrev' => 'BMF',
                                'name' => 'Federal Ministry of Finance',
        ),
                        '1' => array(
                                'code' =>'AT-10',
                                'country' => 'Australia',
                                'abbrev' => 'BMLFUW',
                                'name' => 'Ministry for Agriculture and Environment',
        ),
        //                ),
        //                'error' =>array(),

        );

        $data = json_encode($data);
        //        $data = json_decode($data);
        //        print_r($data->data);exit();
        //        $countries = new YIPL_CodelistCollection_CountriesCollection();

        $actual = YIPL_CodelistCollection_BilateralAidAgencyCodesCollection::Process($data);
        print_r(json_encode($actual));

        /*$e = new YIPL_CodelistCollection_Objects_Country();
         $e->setCode("AF");
         $e->setName("AFGHANISTAN");

         $e1 = new YIPL_CodelistCollection_Objects_Country();
         $e1->setCode("NP");
         $e1->setName("NEPAL");*/

        $data1 = array(
                'codelist' => array(
                    'bilateral-aid-agency-codes' => array(
                       '0' => array(
                                'code' =>'AT-1',
                                'country' => 'Australia',
                                'abbrev' => 'BMF',
                                'name' => 'Federal Ministry of Finance',
        ),
                        '1' => array(
                                'code' =>'AT-10',
                                'country' => 'Australia',
                                'abbrev' => 'BMLFUW',
                                'name' => 'Ministry for Agriculture and Environment',
        ),
        ),
        ),
        //                'error' =>array(),

        );
        $expected = json_encode($data1);

        $this->assertEquals($expected, json_encode($actual));
    }

    public function testVocabulary()
    {
        $data = array(
                    '0' => array(
                            'code' =>'ADT',
                            'name' => 'AidData',
        ),
                    '1' => array(
                            'code' =>'COFOG',
                            'name' => 'Classification of the Functions of Government (UN)',
        ),
        );

        //        $data = json_encode($data);
        $actual = YIPL_CodelistCollection_VocabularyCollection::Process(json_encode($data));
        print_r(json_encode($actual));

        $data1 = array(
                'codelist' => array(
                    'vocabulary' => array(
                        '0' => array(
                            'code' =>'ADT',
                            'name' => 'AidData',
        ),
                        '1' => array(
                            'code' =>'COFOG',
                            'name' => 'Classification of the Functions of Government (UN)',
        ),
        ),
        ),
        //                'error' =>array(),

        );
        $expected = json_encode($data1);

        $this->assertEquals($expected, json_encode($actual));

    }

    public function testVerificationStatus()
    {
        $data = array(
                    '0' => array(
                            'code' =>'0',
                            'description' => 'Not verified',
        ),
                    '1' => array(
                            'code' =>'1',
                            'description' => 'Verified',
        ),
        );

        $actual = YIPL_CodelistCollection_VerificationStatusCollection::Process(json_encode($data));
        print_r(json_encode($actual));
        $data1 = array(
                'codelist' => array(
                    'verification-status' => array(
                        '0' => array(
                            'code' =>'0',
                            'description' => 'Not verified',
        ),
                    '1' => array(
                            'code' =>'1',
                            'description' => 'Verified',
        ),
        ),
        ),
        );
        $expected = json_encode($data1);
        $this->assertEquals($expected, json_encode($actual));

    }

    public function testTransactionType()
    {
        $data = array(
                    '0' => array(
                            'code' =>'C',
                            'name' => 'Commitment',
                            'description' => 'A firm written obligation by the donor to provide resources of a specified amount under specified financial terms and conditions and for specified purposes for the benefit of the recipient',
        ),
                    '1' => array(
                            'code' =>'1',
                            'name' => 'Disbursement',
                            'description' => 'The amount placed at the disposal of a recipient country or agency (in the case of internal development-related expenditures, the outlay of funds)',
        ),
        );

        $actual = YIPL_CodelistCollection_TransactionTypeCollection::Process(json_encode($data));
        print_r(json_encode($actual));
        $data1 = array(
                'codelist' => array(
                    'transaction-type' => array(
                        '0' => array(
                            'code' =>'C',
                            'name' => 'Commitment',
                            'description' => 'A firm written obligation by the donor to provide resources of a specified amount under specified financial terms and conditions and for specified purposes for the benefit of the recipient',
        ),
                    '1' => array(
                            'code' =>'1',
                            'name' => 'Disbursement',
                            'description' => 'The amount placed at the disposal of a recipient country or agency (in the case of internal development-related expenditures, the outlay of funds)',
        ),
        ),
        ),
        );
        $expected = json_encode($data1);
        $this->assertEquals($expected, json_encode($actual));

    }

    public function testTiedStatus()
    {
        $data = array(
                    '0' => array(
                            'code' =>'4',
                            'name' => ' Tied ',
                            'description' => ' Official grants or loans where procurement of the goods or services involved is limited to the donor country or to a group of countries which does not include substantially all aid recipient countries. ',
        ),
                    '1' => array(
                            'code' =>'4',
                            'name' => ' Tied ',
                            'description' => ' Official grants or loans where procurement of the goods or services involved is limited to the donor country or to a group of countries which does not include substantially all aid recipient countries. ',
        ),
        );

        $actual = YIPL_CodelistCollection_TiedStatusCollection::Process(json_encode($data));
        print_r(json_encode($actual));
        $data1 = array(
                'codelist' => array(
                    'transaction-type' => array(
                        '0' => array(
                            'code' =>'4',
                            'name' => ' Tied ',
                            'description' => ' Official grants or loans where procurement of the goods or services involved is limited to the donor country or to a group of countries which does not include substantially all aid recipient countries. ',
        ),
                    '1' => array(
                            'code' =>'4',
                            'name' => ' Tied ',
                            'description' => ' Official grants or loans where procurement of the goods or services involved is limited to the donor country or to a group of countries which does not include substantially all aid recipient countries. ',
        ),
        ),
        ),
        );
        $expected = json_encode($data1);
        $this->assertEquals($expected, json_encode($actual));

    }

    public function testSector()
    {
        $data = array(
                    '0' => array(
                            'code' =>'4',
                            'name' => ' Tied ',
                            'description' => ' Official grants or loans where procurement of the goods or services involved is limited to the donor country or to a group of countries which does not include substantially all aid recipient countries. ',
        ),
                    '1' => array(
                            'code' =>'4',
                            'name' => ' Tied ',
                            'description' => ' Official grants or loans where procurement of the goods or services involved is limited to the donor country or to a group of countries which does not include substantially all aid recipient countries. ',
        ),
        );

        $actual = YIPL_CodelistCollection_SectorCollection::Process(json_encode($data));
        print_r(json_encode($actual));
        $data1 = array(
                'codelist' => array(
                    'sector' => array(
                        '0' => array(
                            'code' =>'4',
                            'name' => ' Tied ',
                            'description' => ' Official grants or loans where procurement of the goods or services involved is limited to the donor country or to a group of countries which does not include substantially all aid recipient countries. ',
        ),
                    '1' => array(
                            'code' =>'4',
                            'name' => ' Tied ',
                            'description' => ' Official grants or loans where procurement of the goods or services involved is limited to the donor country or to a group of countries which does not include substantially all aid recipient countries. ',
        ),
        ),
        ),
        );
        $expected = json_encode($data1);
        $this->assertEquals($expected, json_encode($actual));

    }

    public function testResultType()
    {
        $data = array(
                    '0' => array(
                            'code' =>'1',
                            'name' => 'Output',
        ),
                    '1' => array(
                            'code' =>'2',
                            'name' => 'Outcome',
        ),
        );

        $actual = YIPL_CodelistCollection_ResultTypeCollection::Process(json_encode($data));
        print_r(json_encode($actual));
        $data1 = array(
                'codelist' => array(
                    'result-type' => array(
                        '0' => array(
                            'code' =>'1',
                            'name' => 'Output',
        ),
                    '1' => array(
                            'code' =>'2',
                            'name' => 'Outcome',
        ),
        ),
        ),
        );
        $expected = json_encode($data1);
        $this->assertEquals($expected, json_encode($actual));

    }

    public function testRelatedActivityType()
    {
        $data = array(
                    '0' => array(
                            'code' =>'1',
                            'name' => 'Parent',
                            'description' => 'An activity that contains sub-activities (sub-components)',

        ),
                    '1' => array(
                            'code' =>'2',
                            'name' => 'Child',
                            'description' => 'An activity that contains sub-activities (sub-components)',
        ),
        );

        $actual = YIPL_CodelistCollection_RelatedActivityTypeCollection::Process(json_encode($data));
        print_r(json_encode($actual));
        $data1 = array(
                'codelist' => array(
                    'related-activity-type' => array(
                         '0' => array(
                            'code' =>'1',
                            'name' => 'Parent',
                            'description' => 'An activity that contains sub-activities (sub-components)',

        ),
                    '1' => array(
                            'code' =>'2',
                            'name' => 'Child',
                            'description' => 'An activity that contains sub-activities (sub-components)',
        ),
        ),
        ),
        );
        $expected = json_encode($data1);
        $this->assertEquals($expected, json_encode($actual));

    }

    public function testRegion()
    {
        $data = array(
                    '0' => array(
                            'code' =>'89',
                            'name' => 'Europe, Regional',
        ),
                    '1' => array(
                            'code' =>'189',
                            'name' => 'Africa',
        ),
        );

        $actual = YIPL_CodelistCollection_RegionCollection::Process(json_encode($data));
        print_r(json_encode($actual));
        $data1 = array(
                'codelist' => array(
                    'region' => array(
                         '0' => array(
                            'code' =>'89',
                            'name' => 'Europe, Regional',
        ),
                    '1' => array(
                            'code' =>'189',
                            'name' => 'Africa',
        ),
        ),
        ),
        );
        $expected = json_encode($data1);
        $this->assertEquals($expected, json_encode($actual));

    }

    public function testPolicySignificance()
    {
        $data = array(
                    '0' => array(
                            'code' =>'0',
                            'name' => 'not targeted',
                            'description' => 'The score “not targeted” means that the activity was examined but found not to target the policy objective.'
                            ),
                    '1' => array(
                            'code' =>'0',
                            'name' => 'not targeted',
                            'description' => 'The score “not targeted” means that the activity was examined but found not to target the policy objective.'
                            ),
                            );

                            $actual = YIPL_CodelistCollection_PolicySignificanceCollection::Process(json_encode($data));
                            print_r(json_encode($actual));
                            $data1 = array(
                'codelist' => array(
                    'policy-significance' => array(
                         '0' => array(
                            'code' =>'0',
                            'name' => 'not targeted',
                            'description' => 'The score “not targeted” means that the activity was examined but found not to target the policy objective.'
                            ),
                    '1' => array(
                            'code' =>'0',
                            'name' => 'not targeted',
                            'description' => 'The score “not targeted” means that the activity was examined but found not to target the policy objective.'
                            ),
                            ),
                            ),
                            );
                            $expected = json_encode($data1);
                            $this->assertEquals($expected, json_encode($actual));

    }

    public function testPublisherType()
    {
        $data = array(
                    '0' => array(
                            'code' =>'1',
                            'name' => 'Aid Provider',
        ),
                    '1' => array(
                            'code' =>'0',
                            'name' => 'not targeted',
        ),
        );

        $actual = YIPL_CodelistCollection_PublisherTypeCollection::Process(json_encode($data));
        print_r(json_encode($actual));
        $data1 = array(
                'codelist' => array(
                    'policy-significance' => array(
                         '0' => array(
                            'code' =>'1',
                            'name' => 'Aid Provider',
        ),
                    '1' => array(
                            'code' =>'0',
                            'name' => 'not targeted',
        ),
        ),
        ),
        );
        $expected = json_encode($data1);
        $this->assertEquals($expected, json_encode($actual));

    }

    public function testOrganisationType()
    {
        $data = array(
                    '0' => array(
                            'code' =>'1',
                            'name' => 'Aid Provider',
        ),
                    '1' => array(
                            'code' =>'0',
                            'name' => 'not targeted',
        ),
        );

        $actual = YIPL_CodelistCollection_OrganisationTypeCollection::Process(json_encode($data));
        print_r(json_encode($actual));
        $data1 = array(
                'codelist' => array(
                    'policy-significance' => array(
                         '0' => array(
                            'code' =>'1',
                            'name' => 'Aid Provider',
        ),
                    '1' => array(
                            'code' =>'0',
                            'name' => 'not targeted',
        ),
        ),
        ),
        );
        $expected = json_encode($data1);
        $this->assertEquals($expected, json_encode($actual));

    }

    public function testOrganisationRole()
    {
        $data = array(
                    '0' => array(
                            'code' => 'Funding',
                            'name' => 'Aid Provider',
        ),
                    '1' => array(
                            'code' =>'Extending',
                            'name' => 'not targeted',
        ),
        );

        $actual = YIPL_CodelistCollection_OrganisationRoleCollection::Process(json_encode($data));
        print_r(json_encode($actual));
        $data1 = array(
                'codelist' => array(
                    'policy-significance' => array(
                         '0' => array(
                            'code' => 'Funding',
                            'name' => 'Aid Provider',
        ),
                    '1' => array(
                            'code' =>'Extending',
                            'name' => 'not targeted',
        ),
        ),
        ),
        );
        $expected = json_encode($data1);
        $this->assertEquals($expected, json_encode($actual));

    }

    public function testPolicyMarker()
    {
        $data = array(
                    '0' => array(
                            'code' => '1',
                            'name' => 'Gender Equality',
        ),
                    '1' => array(
                            'code' => '1',
                            'name' => 'Gender Equality',
        ),
        );

        $actual = YIPL_CodelistCollection_PolicyMarkerCollection::Process(json_encode($data));
        print_r(json_encode($actual));
        $data1 = array(
                'codelist' => array(
                    'policy-marker' => array(
                         '0' => array(
                            'code' => '1',
                            'name' => 'Gender Equality',
        ),
                    '1' => array(
                            'code' => '1',
                            'name' => 'Gender Equality',
        ),
        ),
        ),
        );
        $expected = json_encode($data1);
        $this->assertEquals($expected, json_encode($actual));

    }
    
public function testOrganisationIdentifierMultilateral()
    {
        $data = array(
                    '0' => array(
                            'code' => '1',
                            'abbrev' => 'JPT',
                            'country' => '',
                            'name' => 'Gender Equality',
        ),
                    '1' => array(
                            'code' => '1',
                            'abbrev' => 'JPT',
                            'country' => '',
                            'name' => 'Gender Equality',
        ),
        );

        $actual = YIPL_CodelistCollection_OrganisationIdentifierMultilateralCollection::Process(json_encode($data));
        print_r(json_encode($actual));
        $data1 = array(
                'codelist' => array(
                    'organisation-identifier-multilateral' => array(
                         '0' => array(
                            'code' => '1',
                            'country' => '',
                            'abbrev' => 'JPT',
                            'name' => 'Gender Equality',
        ),
                    '1' => array(
                            'code' => '1',
                            'country' => '',
                            'abbrev' => 'JPT',
                            'name' => 'Gender Equality',
        ),
        ),
        ),
        );
        $expected = json_encode($data1);
        $this->assertEquals($expected, json_encode($actual));

    }


public function testOrganisationIdentifierIngo()
    {
        $data = array(
                    '0' => array(
                            'code' => '1',
                            'abbrev' => 'JPT',
                            'country' => '',
                            'name' => 'Gender Equality',
        ),
                    '1' => array(
                            'code' => '1',
                            'abbrev' => 'JPT',
                            'country' => '',
                            'name' => 'Gender Equality',
        ),
        );

        $actual = YIPL_CodelistCollection_OrganisationIdentifierIngoCollection::Process(json_encode($data));
//        print_r(json_encode($actual));
        $data1 = array(
                'codelist' => array(
                    'organisation-identifier-ingo' => array(
                         '0' => array(
                            'code' => '1',
                            'country' => '',
                            'abbrev' => 'JPT',
                            'name' => 'Gender Equality',
        ),
                    '1' => array(
                            'code' => '1',
                            'country' => '',
                            'abbrev' => 'JPT',
                            'name' => 'Gender Equality',
        ),
        ),
        ),
        );
        
        $arrayToXml = new YIPL_ArrayToXml();
        $xml = $arrayToXml->array2xml($actual['codelist'], null, 'codelist', null);
        print_r($xml);exit();
        $expected = json_encode($data1);
        $this->assertEquals($expected, json_encode($actual));

    }
}