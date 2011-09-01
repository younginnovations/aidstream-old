<?php
class Iati_Activity_Element_Location_Description extends Iati_Activity_Element
{
    protected $_type = 'Description';
    protected $_parentType = 'Location';
    protected $_validAttribs = array('text' => '', '@xml_lang' => '');
}