<?php
class Iati_Activity_Element_Location_Name extends Iati_Activity_Element
{
    protected $_type = 'Name';
    protected $_parentType = 'Location';
    protected $_validAttribs = array('text' => '', '@xml_lang' => '');
}