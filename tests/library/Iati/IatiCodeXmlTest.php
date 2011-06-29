<?php

class Iati_IatiCodeXmlTest extends PHPUnit_Framework_TestCase
{
    /**
     * The getDirectoryFiles method should return all the files inside a folder.
     *
     * The method should not do anything else but read the source folder and return it
     */
    public $config;
    public function setup()
    {
        // Assign and instantiate in one step:
        //        $this->bootstrap = new Zend_Application(
        $this->config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini',
                                    'testing');
        parent::setUp();
    }
     
    public function testGetDirectoryFiles()
    {
        $iatiCodeXml = new Iati_IatiCodeXml();
        $iatiCodeXml->setSource(TEST_PATH . '/files/iaticodexmltest/iati_master/en/codes/organisation');

        $files = $iatiCodeXml->getDirectoryFiles();

//        print_r($files);
        $expectedFiles = array(
        TEST_PATH . '/files/iaticodexmltest/iati_master/en/codes/organisation/organisation_identifier_bilateral.csv',
        TEST_PATH . '/files/iaticodexmltest/iati_master/en/codes/organisation/organisation_identifier_ingo.csv',
        TEST_PATH . '/files/iaticodexmltest/iati_master/en/codes/organisation/organisation_identifier_multilateral.csv',
        TEST_PATH . '/files/iaticodexmltest/iati_master/en/codes/organisation/sample.csv',


        );

        $this->assertEquals($expectedFiles, $files);
    }

    public function testParseDirectory()
    {
        $testSourcePath = TEST_PATH . '/files/iaticodexmltest/iati_master/';
        $iatiCodeXml = new Iati_IatiCodeXml();
        $iatiCodeXml->setSource($testSourcePath);
        $files1 = $iatiCodeXml->parseDirectory();
//        print_r($files1);

        $expectedFiles = array(
        TEST_PATH . '/files/iaticodexmltest/iati_master/en/codes/organisation',
        TEST_PATH . '/files/iaticodexmltest/iati_master/en/codes/sector',
        TEST_PATH . '/files/iaticodexmltest/iati_master/en/codes/standard',
        TEST_PATH . '/files/iaticodexmltest/iati_master/fr/codes/organisation',
        TEST_PATH . '/files/iaticodexmltest/iati_master/fr/codes/sector',
        TEST_PATH . '/files/iaticodexmltest/iati_master/fr/codes/standard',
        );
        $this->assertEquals($expectedFiles, $files1);
    }


    /**
     * Builds XML structure from read files.
     *
     * Main function of IatiCodeXml responsible for reading the files, build array structure and then convert it
     * into XML structure.
     */
    public function testBuildFromFiles()
    {
        $iatiCodeXml = new Iati_IatiCodeXml();
        $iatiCodeXml->setSource(TEST_PATH . '/files/iaticodexmltest/iati_master/en/codes/organisation');
        $iatiCodeXml->setLanguage('en');
        $iatiCodeXml->setType('organisation');
        $iatiCodeXml->setVersion('1.01');
        $iatiCodeXml->setDest(TEST_PATH . '/files/iaticodexmltest/output/iaticodes_en_organisation.xml');

        $iatiCodeXml->buildFromFiles();

        $expectedFilepath = TEST_PATH . '/files/iaticodexmltest/output/iaticodes_en_organisation.xml';
        $this->assertTrue(file_exists($expectedFilepath));
    }

    /**
     * Test array2xml function.
     *
     * Modification of original array_2_xml function which allows repeatation of same code using ##
     */
    public function testArrayToXml()
    {
        $array = array(
            'iaticodelist' => array(
                '##' => 1,
        0 => array(
                    '#id' => 1,
                    'code' => array(
                        '##' => 1,
        array(
                            'a' => 'b',
                            'c' => 'd',
        ),
        array(
                            'a' => 'b',
                            'c' => 'd',
        ),
        ),
        ),
        1 => array(
                    '#id' => 2,
                    'code' => array(
                        '##' => 1,
        0 => array(
                            'e' => 'f',
                            'g' => 'h',
        ),
        ),
        ),
        ),
        );

        $iatiCodeXml = new Iati_IatiCodeXml();
        $xml = $iatiCodeXml->array2xml($array, null, 'iaticodelists');

        $expectedString = '<?xml version="1.0" encoding="utf-8"?>
<iaticodelists><iaticodelist id="1"><code><a>b</a><c>d</c></code><code><a>b</a><c>d</c></code></iaticodelist><iaticodelist id="2"><code><e>f</e><g>h</g></code></iaticodelist></iaticodelists>
';


        $this->assertEquals($expectedString, $xml);
    }

    /**
     * Tests getFileContent function
     *
     * FileContent function takes the file path of a csv file and then converts it into suitable array format
     * to be later on changed into xml.
     */
    public function testGetFileContent()
    {
        $iatiCodeXml = new Iati_IatiCodeXml();
        $srcFilepath = TEST_PATH . '/files/iaticodexmltest/iati_master/en/codes/organisation/sample.csv';
        $array = $iatiCodeXml->getFileContent($srcFilepath);

        $expectedArray = array(
            '##' => 1,
        array(
                '#id' => 'A1',
                'desc1' => 'Austria',
                'desc2' => 'BMF',
                'desc3' => 'Federal Ministry of Finance',
        ),
        array(
                '#id' => 'A2',
                'desc1' => 'Austria',
                'desc2' => 'BMLFUW',
                'desc3' => 'Ministry for Agriculture and Environment',
        ),
        array(
                '#id' => 'A3',
                'desc1' => 'B3',
                'desc2' => 'D3',
                'desc3' => 'H3',
        ),
        array(
                '#id' => 'A4',
                'desc1' => 'codE DeleTeD - Do noT USe',
                'desc2' => 'C4',
                'desc3' => 'D4',
                'desc4' => 'E4',
        ),
        array(
                '#id' => 'A5',
        ),
        );
        $this->assertEquals($expectedArray, $array);
    }


    public function testCharacterEncode()
    {
        $iatiCodeXml = new Iati_IatiCodeXml();

        $array = array(
            'iaticodelist' => array(
                '#id' => 'A1',
                'desc1' => 'Testing & and < and > signs',
        ),
        );

        $xml = $iatiCodeXml->array2xml($array, null, 'iaticodelists');

        $expectedString = '<?xml version="1.0" encoding="utf-8"?>
<iaticodelists><iaticodelist id="A1"><desc1>Testing &amp; and &lt; and &gt; signs</desc1></iaticodelist></iaticodelists>
';
        $this->assertEquals($expectedString, $xml);
    }


    //=============================================================================================
    /**
    *
    * test cases for iatiController
    *
    */

    public function testmissXmlUrlValidator()
    {
        $postValues = array('schemaVersion' => '1',
                                    '', //for missing parameter
        );
        $client = new Zend_Http_Client($this->config->test->url.'iati/xmlurlvalidator');
        foreach ($postValues as $key => $value) {
            $client->setParameterPost($key, $value);
        }
        $client->setMethod('POST');
        $jsonData = $client->request()->getBody();
        print $client->request()->getHeader('Content-type');print $jsonData;
        $expected = '{"status":"fail","error":["Missing parameter \"xmlUrl\""]}';
        $this->assertEquals($expected, $jsonData);
    }
    public function testfailXmlUrlValidator()
    {
        $postValues = array('schemaVersion' => '1',
                               'xmlUrl' => $this->config->xml->fail->url, 
        //                            '', //for missing parameter
        );
        $client = new Zend_Http_Client($this->config->test->url.'iati/xmlurlvalidator');
        foreach ($postValues as $key => $value) {
            $client->setParameterPost($key, $value);
        }
        $client->setMethod('POST');
        $jsonData = $client->request()->getBody();
        print $client->request()->getHeader('Content-type');print $jsonData;
        //        $expected = '{"status":"fail","error":["Invalid Schema Version"]}';
        $expected = '{"status":"fail","error":["XML error \"expected '>'\n\" [3] (Code 73) in \/tmp\/phpLuR6k7 on line 2843 column 3\n","XML error \"Extra content at the end of the document\n\" [3] (Code 5) in \/tmp\/phpLuR6k7 on line 2843 column 3\n"]}';

        $this->assertEquals($expected, $jsonData);
    }

    public function testpassXmlUrlValidator()
    {
        $postValues = array('schemaVersion' => '1',
                               'xmlUrl' => $this->config->xml->pass->url, 
        );
        $client = new Zend_Http_Client($this->config->test->url.'iati/xmlurlvalidator');
        foreach ($postValues as $key => $value) {
            $client->setParameterPost($key, $value);
        }
        $client->setMethod('POST');
        $jsonData = $client->request()->getBody();
        print $client->request()->getHeader('Content-type');
        print $jsonData;
        $expected = '{"status":"pass"}';
        //        $expected = '{"status":"fail","error":["Missing parameter \"xmlUrl\""]}';
        $this->assertEquals($expected, $jsonData);
    }

    public function testpassXmlValidator()
    {
         
        $postValues = array('schemaVersion' => '1',
        );
        $client = new Zend_Http_Client($this->config->test->url.'iati/xmlvalidator');
        //        $text = 'this is some plain text';
        foreach ($postValues as $key => $value) {
            $client->setParameterPost($key, $value);
        }
        $client->setFileUpload($this->config->iati->xml->file, 'xml');
        $client->request('POST');
        print $client->request()->getHeader('Content-type');
        $jsonData = $client->request()->getBody();

//        print $jsonData;
        $expected = '{"status":"pass"}';
        //        $expected = '{"status":"fail","error":["Invalid Schema Version"]}';
        //                $expected = '{"status":"fail","error":["Missing parameter \"xmlUrl\""]}';
        $this->assertEquals($expected, $jsonData);

    }

    public function testfailXmlValidator()
    {
        $postValues = array('schemaVersion' => '1',
        );
        $client = new Zend_Http_Client($this->config->test->url.'iati/xmlvalidator');
        foreach ($postValues as $key => $value) {
            $client->setParameterPost($key, $value);
        }
        $client->setFileUpload($this->config->iati->wrong->xml->file, 'xml');
        $client->request('POST');
        print $client->request()->getHeader('Content-type');
        $jsonData = $client->request()->getBody();
        print $jsonData;
        //                $expected = '{"status":"pass"}';
        //        $expected = '{"status":"fail","error":["Invalid Schema Version"]}';
        $expected = '{"status":"fail","error":["XML error \"expected '>'\n\" [3] (Code 73) in \/tmp\/phpLuR6k7 on line 2843 column 3\n","XML error \"Extra content at the end of the document\n\" [3] (Code 5) in \/tmp\/phpLuR6k7 on line 2843 column 3\n"]}';
        $this->assertEquals($expected, $jsonData);

    }

    public function testmissXmlValidator()
    {
        $client = new Zend_Http_Client($this->config->test->url.'iati/xmlvalidator');
        $client->request('POST');
        print $client->request()->getHeader('Content-type');
        $jsonData = $client->request()->getBody();
        print $jsonData;
        $expected = '{"status":"fail","error":["xml file missing"]}';
        $this->assertEquals($expected, $jsonData);
    }

    public function testpassGenericXmlValidator()
    {
        $postValues = array('schemaVersion' => '1',
        );
        $client = new Zend_Http_Client($this->config->test->url.'iati/genericxmlvalidator');
        //        $text = 'this is some plain text';
        foreach ($postValues as $key => $value) {
            $client->setParameterPost($key, $value);
        }
        $client->setFileUpload($this->config->generic->xml->file, 'xml');
        $client->setFileUpload($this->config->generic->xsd->file, 'xsd');
        $client->request('POST');

        $jsonData = $client->request()->getBody();

//        print $jsonData;
        $expected = '{"status":"pass"}';
        //        $expected = '{"status":"fail","error":["Invalid Schema Version"]}';
        //                $expected = '{"status":"fail","error":["Missing parameter \"xmlUrl\""]}';
        $this->assertEquals($expected, $jsonData);

    }

    public function testfailGenericXmlValidator()
    {
       
        $client = new Zend_Http_Client($this->config->test->url.'iati/genericxmlvalidator');
        
        $client->setFileUpload($this->config->generic->wrong->xml->file, 'xml');
        $client->setFileUpload($this->config->generic->wrong->xsd->file, 'xsd');
        $client->request('POST');

        $jsonData = $client->request()->getBody();

        print $jsonData;
        $expected =  '{"status":"fail","error":["XML error \"expected '>'\n\" [3] (Code 73) in \/tmp\/phpzIUQwS on line 2842 column 3\n"]}';
        //        $expected = '{"status":"fail","error":["Invalid Schema Version"]}';
        //                $expected = '{"status":"fail","error":["Missing parameter \"xmlUrl\""]}';
        $this->assertEquals($expected, $jsonData);

    }

    public function testmissGenericXmlValidator()
    {
       
        $client = new Zend_Http_Client($this->config->test->url.'iati/genericxmlvalidator');
       
       /* $client->setFileUpload($this->config->generic->wrong->xml->file, 'xml');
        $client->setFileUpload($this->config->generic->wrong->xsd->file, 'xsd');*/
        $client->request('POST');

        $jsonData = $client->request()->getBody();

        print $jsonData;
        $expected = '{"status":"fail","error":["xml file missing","xsd file missing"]}';
        $this->assertEquals($expected, $jsonData);

    }
    
    public function testIatiCodexmlFinal()
    {
        $sourcepath = $this->config->iati->source;
        
        $parseDirectory = new Iati_IatiCodeXml();
        $parseDirectory->setSource($sourcepath);
        $files = $parseDirectory->parseDirectory();// gets the inner folder
        $xmlversion = trim(file_get_contents($this->config->iati->versionfilepath.'current_version.txt'));
        foreach($files as $eachfile){
            $xmlConverter = new Iati_IatiCodeXml();
            $xmlConverter->setSource($eachfile);
            $foldername = explode('/', $eachfile);
            $count = count($foldername);
            $lang = $foldername[$count-3];
            $type = $foldername[$count-1];
            $xmlConverter->setLanguage($lang);
            $xmlConverter->setType($type);
            $xmlConverter->setVersion($xmlversion);
            $destination = $this->config->iati->destination.'iaticodes_'.$lang.'_'.$type.'.xml';
            $xmlConverter->setDest($destination);
            //            $xmlConverter->getDirectoryFiles();
            $xmlConverter->buildFromFiles();

        }
    }
}