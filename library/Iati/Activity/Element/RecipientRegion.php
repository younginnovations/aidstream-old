<?php
class Iati_Activity_Element_RecipientRegion extends Iati_Activity_Element
{
    protected $_type = 'RecipientRegion';
    protected $_parentType = 'Activity';
    protected $_validAttribs = array('text' => '', '@xml_lang' => '',
                                     '@percentage' => '', '@code' => '');
}
