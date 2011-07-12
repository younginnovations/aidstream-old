<?php
class Iati_WEP_TreeRegistryTest extends PHPUnit_Framework_TestCase
{
    public function setup()
    {

    }

    public function testTree()
    {
        $initial = array(
                        'text'=> 'manisha',
                        'xml_lang'=> '363',
                    );
                    
        $title = new Iati_WEP_Activity_Title();
        $title->setAccountActivity(array('account_id'=>'2', 'activity_id'=> '2'));
        $title->propertySetter($initial);
//        print_r($title);exit;
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
//        print_r($registryTree);
        
        $registryTree->addNode($title);
        $a = $registryTree->xml();
        print_r($a);
        
        $newTitle = new Iati_WEP_Activity_Title();
        $newTitle->setAccountActivity(array('account_id'=>'2', 'activity_id'=> '2'));
        $newTitle->propertySetter($initial);
        $registryTree->addNode($newTitle, $title);
        $a = $registryTree->xml();
        print_r($a);
        print_r($registryTree->getChildNodes($title));
        
        $initial = array(
                        'text'=> 'manisha',
                        'xml_lang'=> '363',
                    );
        
    }
}