<?php
class Iati_Activity_Element_Sector extends Iati_Activity_Element
{
    protected $_type = 'Sector';
    protected $_parentType = 'Activity';
    protected $_validAttribs = array('text' => '', '@xml_lang' => '',
                                     '@vocabulary' => '', '@code' => '',
                                     '@percentage' => '');
}
