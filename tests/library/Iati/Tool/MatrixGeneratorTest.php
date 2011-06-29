<?php
class MatrixGeneratorTest extends PHPUnit_Framework_TestCase
{
    public function testRef()
    {
        $matrixGenerator = new Iati_Tool_MatrixGenerator();
        $matrixGenerator->setFolderPath("/home/manisha/Documents/iati_docs/standard/");
        $result = $matrixGenerator->ref('1001');
        print $result;print "\n";
        $expected = '1';
        $this->assertEquals($expected, $result);
        
        $array = array('GB-1', 'GB', '12e');
        $matrixGenerator->setFolderPath("/home/manisha/Documents/iati_docs/standard/");
        $result1 = $matrixGenerator->ref($array);
        print $result1;
        $actual = '';
        $expected = '';
        $this->assertEquals($expected, $actual);
    }
    
//    public function test
    
    
}