<?php
class Iati_Activity_Element_Transaction extends Iati_Activity_Element
{
    protected $_type = 'Transaction';
    protected $_parentType = 'Activity';
    protected $_validAttribs = array('@ref' => '');
}
