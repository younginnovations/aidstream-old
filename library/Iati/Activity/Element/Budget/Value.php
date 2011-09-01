<?php
class Iati_Activity_Element_Budget_Value extends Iati_Activity_Element
{
    protected $_type = 'Value';
    protected $_parentType = 'Budget';
    protected $_validAttribs = array('text' => '', '@value_date' => '', '@currency' => '');
}
