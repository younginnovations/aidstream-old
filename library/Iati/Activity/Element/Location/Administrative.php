<?php
class Iati_Activity_Element_Location_Administrative extends Iati_Activity_Element
{
    protected $_type = 'Administrative';
    protected $_parentType = 'Location';
    protected $_validAttribs = array('text' => '', '@xml_lang' => '');
}