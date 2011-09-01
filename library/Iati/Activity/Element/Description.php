<?php
class Iati_Activity_Element_Description extends Iati_Activity_Element
{
    protected $_type = 'Description';
    protected $_parentType = 'Activity';
    protected $_validAttribs = array('text' => '', '@xml_lang' => '');
}
