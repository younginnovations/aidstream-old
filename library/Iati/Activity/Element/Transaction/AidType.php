<?php
class Iati_Activity_Element_Transaction_AidType extends Iati_Activity_Element
{
    protected $_type = 'AidType';
    protected $_parentType = 'Transaction';
    protected $_validAttribs = array('text' => '', '@xml_lang' => '', '@code' => '');
}
