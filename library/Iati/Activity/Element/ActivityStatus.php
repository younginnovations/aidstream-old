<?php
class Iati_Activity_Element_ActivityStatus extends Iati_Activity_Element
{
    protected $_type = 'ActivityStatus';
    protected $_parentType = 'Activity';
    protected $validAttribs = array('text' => '', '@xml_lang' => '');
}
