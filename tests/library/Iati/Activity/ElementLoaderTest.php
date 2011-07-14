<?php
class Iati_Activity_ElementLoaderTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
//         $this->_loader = Iati_Activity_ElementLoader::getInstance();
    }
    
    public function tearDown()
    {
    
    }
    
    public function testGetInstance()
    {
        $loader = Iati_Activity_ElementLoader::getInstance();
        $this->assertEquals('Zend_Loader_PluginLoader', get_class($loader));
    }
    
    public function testLoaderCanLoadObjects()
    {
        $loader = Iati_Activity_ElementLoader::getInstance();
        $activityStatus = $loader->load('ActivityStatus');
        $this->assertEquals('Iati_Activity_Element_ActivityStatus', $activityStatus);
    }
}
