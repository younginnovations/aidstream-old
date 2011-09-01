<?php
class Iati_Activity_Element_Transaction_Value extends Iati_Activity_Element
{
    protected $_type = 'Value';
    protected $_parentType = 'Transaction';
    protected $_validAttribs = array('text' => '', '@value_date' => '', '@currency' => '');
}
