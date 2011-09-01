<?php
class Iati_Activity_Element_PlannedDisbursement_Value extends Iati_Activity_Element
{
    protected $_type = 'Value';
    protected $_parentType = 'PlannedDisbursement';
    protected $_validAttribs = array('text' => '', '@value_date' => '', '@currency' => '');
}
