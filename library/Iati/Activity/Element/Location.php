<?php
class Iati_Activity_Element_Location extends Iati_Activity_Element
{
    protected $_type = 'Location';
    protected $_parentType = 'Activity';
    protected $_validAttribs = array('text' => '', '@percentage' => '');
}
