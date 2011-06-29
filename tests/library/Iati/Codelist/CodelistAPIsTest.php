<?php
class YIPL_CodelistAPIs extends PHPUnit_Framework_TestCase
{
    public $config;
    public function setup()
    {
        $this->config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini',
                                    'testing');
        parent::setUp();
    }
    public function testCountryAPI()
    {
        /*
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
        
        $d = json_encode($data);
        $e = json_decode($d);
        print_r($e);exit();*/
//        print $this->config->password;exit();
        $country = YIPL_CodelistAPIs_CountryCodelistAPI::Process($this->config->username, $this->config->password, 'English');
        print_r(json_encode($country));
        exit();
        
    }
    
public function testCurrencyAPI()
    {
        /*
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
        
        $d = json_encode($data);
        $e = json_decode($d);
        print_r($e);exit();*/
//        print $this->config->password;exit();
        $country = YIPL_CodelistAPIs_CurrencyCodelistAPI::Process($this->config->username, $this->config->password, 'English');
        print_r(json_encode($country));
        exit();
        
    }
    
    public function testActivityDateTypeCodelistAPI()
    {
        $country = YIPL_CodelistAPIs_ActivityDateTypeCodelistAPI::Process($this->config->username, $this->config->password, 'English');
        print_r(json_encode($country));
        exit();
    }
    
    public function testAPI()
    {
        $api = new Iati_Codelist_API('', 'en');
        print_r($api->getCodelistCollection());exit();
        
    }

}