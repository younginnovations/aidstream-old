<?php
class Iati_Activity_Element_Transaction_Description extends Iati_Activity_Element
{
    protected $_type = 'Description';
    protected $_parentType = 'Transaction';
    protected $_validAttribs = array('text' => '', '@xml_lang' => '');
}
