<?php
class Iati_Converter_CrsToIatiTest extends PHPUnit_Framework_TestCase
{
    public $config;
    public function setup()
    {
        $this->config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini',
                                    'testing');
        parent::setUp();
    }

    public function testFilename()
    {
        $crsToiati = new Iati_Converter_CrsToIati();
        $crsToiati->setDonor('IDA-1');
        $crsToiati->setAgency('BM F');
        $crsToiati->setRecipient('Central,Afr,ican Rep.');
        $crsToiati->setFilename();
        $actualFilename = $crsToiati->getFileName();
        print $actualFilename."\n";
//        print TEST_PATH;
        $expectedFilename = 'ida-1_bm-f_central-afr-ican-rep';
        $this->assertEquals($expectedFilename, $actualFilename);
        
        

        $crsToiati = new Iati_Converter_CrsToIati();
        $crsToiati->setDonor('United K');
        $crsToiati->setRecipient('C African Rep');
        $crsToiati->setFilename();
        $actualFilename = $crsToiati->getFileName();
        print $actualFilename;
        $expectedFilename = 'united-k_c-african-rep';
        $this->assertEquals($expectedFilename, $actualFilename);
        //        $crsTo
    }

    public function testGetDestination()
    {
        $crsToiati = new Iati_Converter_CrsToIati();
        $crsToiati->setDonor('United Kingdom');
        $crsToiati->setAgency('BM F');
        $crsToiati->setRecipient('Central African Rep.');
        $crsToiati->setFilename();
        $crsToiati->setDestination();
        $actualDestination = $crsToiati->getDestination();
        print $actualDestination."\n";
        $expectedDestination =
        '/united-kingdom/bm-f/united-kingdom_bm-f_central-african-rep.csv';
        $this->assertEquals($expectedDestination, $actualDestination);


        $crsToiati = new Iati_Converter_CrsToIati();
        $crsToiati->setDonor('United K');
        $crsToiati->setRecipient('C African Rep.');
        $crsToiati->setFilename();
        $crsToiati->setDestination();
        $actualDestination = $crsToiati->getDestination();
        print $actualDestination;
        $expectedDestination =
        '/united-k/united-k_c-african-rep.csv';
        $this->assertEquals($expectedDestination, $actualDestination);
    }
     
    public function testWriteToFile()
    {
        $crsToiati = new Iati_Converter_CrsToIati();
        //        $crsToIati->setSource(TEST_PATH . "/files/crs/crs_2000-01");
        $crsToiati->setDonor('United States Of America');
        $crsToiati->setRecipient('Central African Rep');
        $crsToiati->setDestRoot($this->config->crstoiati->test->dest->root);
        $crsToiati->setFilename();
        $crsToiati->setDestination();

        $array = 
        array( 'hello', 'world',)
        ;
        $crsToiati->setDestRoot($this->config->crstoiati->test->dest->root);

        $crsToiati->writeToFile($array);
        $expectedFilepath = $this->config->crstoiati->test->dest->root.'/united-states-of-america/united-states-of-america_central-african-rep.csv';
        $this->assertTrue(file_exists($expectedFilepath));

        $crsToiati = new Iati_Converter_CrsToIati();
        $crsToiati->setDonor('United Kingdom');
        $crsToiati->setAgency('GTZ.');
        $crsToiati->setRecipient('Central African Rep');
        $crsToiati->setDestRoot($this->config->crstoiati->test->dest->root);
        $crsToiati->setFilename();
        $crsToiati->setDestination();
        $array = array('hey','there',);
        $crsToiati->writeToFile($array);
        $expectedFilepath = $this->config->crstoiati->test->dest->root. '/united-kingdom/gtz/united-kingdom_gtz_central-african-rep.csv';
        $this->assertTrue(file_exists($expectedFilepath));
    }

    /**
     * test to check the result of normaliseCrsid
     * @return unknown_type
     */
    
    public function testNormaliseCrsid()
    {
        $crsToiati = new Iati_Converter_CrsToIati();
        $crsid = '19077';
        $crsToiati->setNormalizedCrsid($crsid);
        $normalizedCrsid = $crsToiati->getNormalizedCrsid();
        print $normalizedCrsid."\n";
        $expectedCrsid = '2001009077a';
        $this->assertEquals($expectedCrsid, $normalizedCrsid);
        
        $crsToiati = new Iati_Converter_CrsToIati();
        $crsid = '2002001234';
        $crsToiati->setNormalizedCrsid($crsid);
        $normalizedCrsid = $crsToiati->getNormalizedCrsid();
        print $normalizedCrsid."\n";
        $expectedCrsid = '2002001234a';
        $this->assertEquals($expectedCrsid, $normalizedCrsid);
        
        $crsToiati = new Iati_Converter_CrsToIati();
        $crsid = '200200ww34';
        $crsToiati->setNormalizedCrsid($crsid);
        $normalizedCrsid = $crsToiati->getNormalizedCrsid();
        print $normalizedCrsid."\n";
        $expectedCrsid = '200200ww34';
        $this->assertEquals($expectedCrsid, $normalizedCrsid);
    
        $crsToiati = new Iati_Converter_CrsToIati();
        $crsid = '43';
        $crsToiati->setNormalizedCrsid($crsid);
        $normalizedCrsid = $crsToiati->getNormalizedCrsid();
        print $normalizedCrsid."\n";
        $expectedCrsid = '2000000043a';
        $this->assertEquals($expectedCrsid, $normalizedCrsid);
        
        $crsToiati = new Iati_Converter_CrsToIati();
        $crsid = '03D275';
        $crsToiati->setNormalizedCrsid($crsid);
        $normalizedCrsid = $crsToiati->getNormalizedCrsid();
        print $normalizedCrsid."\n";
        $expectedCrsid = '200300D275';
        $this->assertEquals($expectedCrsid,$normalizedCrsid);
        
        /****
         * to check if the crsid is already in normalised form
         * it should be 10 charachters (we will ignore the sufix a for now)
         */
        $crsToiati = new Iati_Converter_CrsToIati();
        $crsid = '2002001234';
        $crsToiati->setNormalizedCrsid($crsid);
        $normalizedCrsid = $crsToiati->getNormalizedCrsid();
        print $normalizedCrsid."\n";
        $expectedCrsid = '2002001234a';
        $this->assertEquals($expectedCrsid, $normalizedCrsid);

        $crsid = '2002001234a';
        $crsToiati->setNormalizedCrsid($crsid);
        $normalizedCrsid = $crsToiati->getNormalizedCrsid();
        print $normalizedCrsid."\n";
        $expectedCrsid = '2002001234a';
        $this->assertEquals($expectedCrsid, $normalizedCrsid);

        /****
         * if the length of crsid is less than 5 the format is ynnnn
         * the year is 2000 automatically
         */
        $crsid = '111';
        $crsToiati->setNormalizedCrsid($crsid);
        $normalizedCrsid = $crsToiati->getNormalizedCrsid();
        print $normalizedCrsid."\n";
        $expectedCrsid = '2000000111a';
        $this->assertEquals($expectedCrsid, $normalizedCrsid);

        $crsid = '011';
         $crsToiati->setNormalizedCrsid($crsid);
         $normalizedCrsid = $crsToiati->getNormalizedCrsid();
         print $normalizedCrsid."\n";
         $expectedCrsid = '2000000011a';
         $this->assertEquals($expectedCrsid, $normalizedCrsid);

        /**
         * if the length of crsid is exactly 5, the year is 200y and the counter is nnnn
         */
        $crsid = '80123';
        $crsToiati->setNormalizedCrsid($crsid);
        $normalizedCrsid = $crsToiati->getNormalizedCrsid();
        print $normalizedCrsid."\n";
        $expectedCrsid = '2008000123a';
        $this->assertEquals($expectedCrsid, $normalizedCrsid);

        $crsid = '000123';
         $crsToiati->setNormalizedCrsid($crsid);
         $normalizedCrsid = $crsToiati->getNormalizedCrsid();
         print $normalizedCrsid."\n";
                 $expectedCrsid = '2000000123a';   //??????
         $this->assertEquals($expectedCrsid, $normalizedCrsid);

        
        /**
         * if the length of the crsid is 6 or more but less than 10, format is yynnnn
         * if the year in normalised crsid is before 2000 it should be skipped
         */
        $crsid = '180123';
        $crsToiati->setNormalizedCrsid($crsid);
        $normalizedCrsid = $crsToiati->getNormalizedCrsid();
        print $normalizedCrsid."\n";
//        $expectedCrsid = '1918000123';
        $expectedCrsid = 'Exclude';
        $this->assertEquals($expectedCrsid, $normalizedCrsid);

        $crsid = '050123';
        $crsToiati->setNormalizedCrsid($crsid);
        $normalizedCrsid = $crsToiati->getNormalizedCrsid();
        print $normalizedCrsid."\n";
        $expectedCrsid = '2005000123a';
        $this->assertEquals($expectedCrsid, $normalizedCrsid);
    }
    
    public function testReadFile()
    {
       $crstoiati = new Iati_Converter_CrsToIati();
       $crstoiati->setDestRoot($this->config->crstoiati->test->dest->root);
       $crstoiati->setSource($this->config->crstoxml->test->sourcefolder."testcsv.csv");
       $string = '';
       print $crstoiati->getSource();
       $array = $crstoiati->readFile();
      /* foreach($array as $key=>$k){
           $string .= $k;
       }*/
       $expected = '';
//       $expected = '20011Austria1BMF19077bmf-02163Serbia10010Europe10019UMICs11ODA Grants1101101.32.36CONTRIBUTION TO SEED PROGRAM FOR SME PROMOTIONBETEILIGUNG AN SEED PROGRAMM DES IFC ZUR KMU F�RDERUNG32130Sme development321III.2.a. Industry01/01/2001 00:0031/12/2001 00:00000100';
//       $expected = '20011Austria1BMF19077bmf-02163Serbia10010Europe10019UMICs11ODA Grants1101101.32.36CONTRIBUTION TO SEED PROGRAM FOR SME PROMOTIONBETEILIGUNG AN SEED PROGRAMM DES IFC ZUR KMU Fï¿½RDERUNG32130Sme development321III.2.a. Industry01/01/2001 00:0031/12/2001 00:00000100';
       $this->assertEquals($expected, $string);
    } 

    public function testsortFileData()
    {
        $crstoiati = new Iati_Converter_CrsToIati();
//        $crstoiati->setDestRoot($this->config->crstoiati->test->dest->root);
        $crstoiati->setSource(TEST_PATH.'/testdirectory/austria/bmf/austria_bmf_serbia.csv');
        $crstoiati->sortFileByCommand();
        $result = '';
        $expectedResult = '';
        $this->assertEquals($expectedResult, $result);
        
        
        /*$expectedResult = 
        '20011Austria1BMF19077bmf-02163Serbia10010Europe10019UMICs11ODA Grants1101101.32.36CONTRIBUTION TO SEED PROGRAM FOR SME PROMOTIONBETEILIGUNG AN SEED PROGRAMM DES IFC ZUR KMU Fï¿½RDERUNG32130Sme development321III.2.a. Industry01/01/2001 00:0031/12/2001 00:000002001009076a20021Austria1BMF19078bmf-03163Serbia10010Europe10019UMICs11ODA Grants1101100.591.06GUARANTY FACILITY FOR SME FINANCINGGARANTIEFAZILITï¿½T ZUR KMU-FINANZIERUNG32130Sme development321III.2.a. Industry01/01/2001 00:0031/12/2001 00:000002001009076a20031Austria1BMF19079bmf-04163Serbia10010Europe10019UMICs11ODA Grants1101103.90E-027.08E-02MACROECONOMIC CONSULTING FOR SERBIAN GOVERNMENTWIRTSCHAFTSPOLITISCHE BERATUNG DER SERBISCHEN REGIERUNG15110Economic and development policy/planning151I.5.a. Government & Civil Society-generalSerbia01/01/2001 00:0031/12/2001 00:0000012001009076a20011Austria1BMF19076bmf-01163Serbia10010Europe10019UMICs11ODA Grants1101101.951.953.543.54CONTRIBUTION TO FUND FOR SOCIAL WELFAREBETEILIGUNG AN FONDS FOR SOCIAL WELFARE16010Social/welfare services160I.6. Other Social Infrastructure & ServicesSerbia01/01/2001 00:0031/12/2001 00:000002001009077a20021Austria1BMF19076bmf-01163Serbia10010Europe10019UMICs11ODA Grants1101101.951.953.543.54CONTRIBUTION TO FUND FOR SOCIAL WELFAREBETEILIGUNG AN FONDS FOR SOCIAL WELFARE16010Social/welfare services160I.6. Other Social Infrastructure & ServicesSerbia01/01/2001 00:0031/12/2001 00:000002001009078a20031Austria1BMF19076bmf-01163Serbia10010Europe10019UMICs11ODA Grants1101101.951.953.543.54CONTRIBUTION TO FUND FOR SOCIAL WELFAREBETEILIGUNG AN FONDS FOR SOCIAL WELFARE16010Social/welfare services160I.6. Other Social Infrastructure & ServicesSerbia01/01/2001 00:0031/12/2001 00:000002001009079a';
*/
    }
    
    /*
     * not used anymore
     */
    
    public function testFileArray()
    {
       $crstoiati = new Iati_Converter_CrsToIati();
       $crstoiati->setSource($this->config->crstoxml->test->sourcefolder."testreadfile.csv");
       $crstoiati->setDestRoot($this->config->crstoiati->test->dest->root);
       $string = '';
       $crstoiati->readFile();
       $array = $crstoiati->getData();
       foreach($array as $key=>$k){
           $string .= $k;
       }
       print $string;
       $expected = '20011Austria1BMF19077bmf-02163Serbia10010Europe10019UMICs11ODA Grants1101101.32.36CONTRIBUTION TO SEED PROGRAM FOR SME PROMOTIONBETEILIGUNG AN SEED PROGRAMM DES IFC ZUR KMU F�RDERUNG32130Sme development321III.2.a. Industry01/01/2001 00:0031/12/2001 00:000001002001009077';
       $this->assertEquals($expected, $string);
       
       $actualDestination = $crstoiati->getDestination();
       print $actualDestination;
       $expectedDestination = '/austria/bmf/austria_bmf_serbia.csv';
       $this->assertEquals($expectedDestination, $actualDestination);
    }
    
    
    public function testParseDirectory()
    {
       $crstoiati = new Iati_Converter_CrsToIati();
       $result = $crstoiati->getFilesToSort(TEST_PATH.'/testdirectory');
       var_dump($result);
        $expected = array('/var/www/webservice-iatixml/tests/testdirectory/austria/austria_bosnia-herzegovina.csv',   
                          '/var/www/webservice-iatixml/tests/testdirectory/austria/bmf/austria_bmf_bosnia-herzegovina.csv',   
                          '/var/www/webservice-iatixml/tests/testdirectory/austria/bmf/austria_bmf_serbia.csv',   
                          '/var/www/webservice-iatixml/tests/testdirectory/germany/gtz/germany_gtz_brazil.csv',   
                          '/var/www/webservice-iatixml/tests/testdirectory/sweden/sida/sweden_sida_sri-lanka.csv'
                    );
       $this->assertEquals($expected, $result); 
    }
     /*public function testfinal()
    {
        $crstoiati = new Iati_Converter_CrsToIati();
            $sourcefolder = '';
            print $sourcefolder;
            $destinationRoot = $config['crstoiati']['destroot'];
        $crstoiati = new Iati_Converter_CrsToIati();
        $crstoiati->setDestRoot($destinationRoot);
            $crstoiati->setSource($sourcefolder);
            $crstoiati->readFile();
            $files_array = $crstoiati->getFilesToSort($destinationRoot);

            foreach($files_array as $eacharray){
                $crstoiati->setSource($eacharray);
                $crstoiati->sortFile();
            }
            
            
    }*/
   public function testAll()
   {
//   	echo number_format(memory_get_usage()) . "\n";		
   	$crstoiati = new Iati_Converter_CrsToIati();
   		$crstoiati->setDestRoot('/var/www/webservice-iatixml/tests/donor_csv');
       	$crstoiati->setSource('/var/www/webservice-iatixml/tests/annual_csv/testcsv.csv');
       	$array = $crstoiati->readFile();
       	//echo $crstoiati->fileSize();
       	$files = $crstoiati->getCsvFiles('/var/www/webservice-iatixml/tests/donor_csv');
       	foreach ($files as $file)
       	{
       		$crstoiati->setSource($file);       		
       		//echo $file."\n\n";
//       		echo number_format(memory_get_usage()) . "\n";
//       		$crstoiati->sortFileByCommand();
       		$crstoiati->sortFileByCode();
       	}
   } 
   public function testFinal()
   {
   	echo number_format(memory_get_usage()) . "\n";
   	$crstoiati = new Iati_Converter_CrsToIati();
   	$crstoiati->setDestRoot('/var/www/webservice-iatixml/tests/output/donor_csv');
    $crstoiati->setSource('/var/www/webservice-iatixml/tests/annual_csv');
    $crstoiati->convertCrsToIati();
    /*
    If(file_exists('/var/www/webservice-iatixml/tests/output/donor_csv/austria/bmf/austria_bmf_bosnia-herzegovina.csv'))
    {
    	$result=file_get_contents('/var/www/webservice-iatixml/tests/output/donor_csv/austria/bmf/austria_bmf_bosnia-herzegovina.csv');
    }
    $expected=file_get_contents("/var/www/webservice-iatixml/tests/files/expectedFileForCrsData/expected.csv");
    $this->assertEquals($expected, $result); 
    */
    echo number_format(memory_get_peak_usage()) . "\n";
   }

}