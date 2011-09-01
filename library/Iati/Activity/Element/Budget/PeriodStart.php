<?php
class Iati_Activity_Element_Budget_PeriodStart extends Iati_Activity_Element
{
    protected $_type = 'PeriodStart';
    protected $_parentType = 'Budget';
    protected $_validAttribs = array('text' => '', '@iso_date' => '');
}
