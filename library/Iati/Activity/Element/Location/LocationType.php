<?php
class Iati_Activity_Element_Location_LocationType extends Iati_Activity_Element
{
    protected $_type = 'LocationType';
    protected $_parentType = 'Location';
    protected $_validAttribs = array('text' => '', '@xml_lang' => '', '@code' => '');
}