<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Iati_Xml_ArrayToXmlTest extends PHPUnit_Framework_TestCase
{
    public function testConversion()
    {
        /*$array = array(
            'products' => array(
                'product1' => array(
                    'name' => 'paper',
                    'size' => 'big',
                    'dimension' => array(
                        'width' => 15,
                        'length' => 5,
                    )
                ),
                'product2' => array(
                    'name' => 'pencil',
                    'size' => 'small',
                    'dimension' => array(
                        'width' => 15,
                        'length' => 5,
                    )
                )
            )
        );*/
      
        $array = array(
               'filename' => array(
                        'codeid1' => array(
                                'desc1' => 'hello',
                                'desc2' => 'hi',
                                    ),
                                    
                          'codeid2' => array(
                                'desc1' => 'hkkello',
                                'desc2' => 'hkki',
                                    )
                        
        )
        
        );
        
        $converter = new Iati_Converter();
        $xml = $converter->array_2_xml($array);

        $expectedXml = '<?xml version="1.0" encoding="utf-8"?>
<data><products><product1><name>paper</name><size>big</size><dimension><width>15</width><length>5</length></dimension></product1><product2><name>pencil</name><size>small</size><dimension><width>15</width><length>5</length></dimension></product2></products></data>
';
print $xml;

file_put_contents('test.xml', $xml);

        $this->assertEquals($expectedXml, $xml);
    }
}