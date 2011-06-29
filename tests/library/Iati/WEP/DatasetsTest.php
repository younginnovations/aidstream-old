<?php
class Iati_WEP_DatasetsTest extends PHPUnit_Framework_TestCase
{
    public function testSetAAAA()
    {
        $array = array('iati_activities', 'iati_activity', 'reporting_org');
        $a = new Iati_WEP_Datasets();
        foreach($array as $eachArray){
            $a->setData($eachArray);
        }
        
        $aArray = $a->getData();
//        $str = serialize($aArray);
//        print $str;
        print_r($aArray);
        $expected = array(
            'iati_activities' => array(
                '@generated-datetime' => array(
                    'input' => 'TextBox',
                ),  
                '@version' => array(
                    'input' => 'TextBox',
                ),
            ),
            'iati_activity' => array(
                '@xml:lang' => array(
                    'input' => 'Select',
                    'table' => 'Language',
                ),
                '@default-currency' => array(
                    'input' => 'Select',
                    'table' =>'Currency',
                ),
                '@hierarchy' => array(
                    'input' => 'TextBox',
                ),
                '@last-updated-datetime' => array(
                    'input' => 'TextBox',
                ),
            ),
            'reporting_org' => array(
                '@ref' => array(
                    'input' => 'Select',
                    'table' =>'ref',
                ),
                '@type' => array(
                    'input' => 'Select',
                    'table' => 'OrganisationType',
                ),
                'text' => array(
                    'input' => 'TextBox',
                ),
                '@xml:lang' => array(
                    'input' => 'Select',
                    'table' => 'Language',
                ),
            ),
        );
        
        $this->assertEquals($expected,$aArray);
    }
}