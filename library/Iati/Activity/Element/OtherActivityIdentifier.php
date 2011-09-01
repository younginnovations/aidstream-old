<?php
class Iati_Activity_Element_OtherActivityIdentifier extends Iati_Activity_Element
{
    protected $_type = 'OtherIdentifier';
    protected $_parentType = 'Activity';
    protected $_validAttribs = array('text'=> '', '@owner_ref' => '', '@owner_name' => '');
}
