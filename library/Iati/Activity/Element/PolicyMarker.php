<?php
class Iati_Activity_Element_PolicyMarker extends Iati_Activity_Element
{
    protected $_type = 'PolicyMarker';
    protected $_parentType = 'Activity';
    protected $_validAttribs = array('text' => '', '@xml_lang' => '',
                                    '@significance' => '', '@code' => '',
                                    '@vocabulary' => '');
}
