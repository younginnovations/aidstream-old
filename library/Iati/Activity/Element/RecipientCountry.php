<?php
class Iati_Activity_Element_RecipientCountry extends Iati_Activity_Element
{
    protected $_type = 'RecipientCountry';
    protected $_parentType = 'Activity';
    protected $_validAttribs = array('text' => '', '@xml_lang' => '',
                                    '@percentage' => '', '@code' => '', );
}
