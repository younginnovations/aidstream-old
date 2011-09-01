<?php
class Iati_Activity_Element_Title extends Iati_Activity_Element
{
    protected $_type = 'Title';
    protected $_parentType = 'Activity';
    protected $_validAttribs = array('text' => '', '@xml_lang' => '');
}
