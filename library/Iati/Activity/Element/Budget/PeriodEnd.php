<?php
class Iati_Activity_Element_Budget_PeriodEnd extends Iati_Activity_Element
{
    protected $_type = 'PeriodEnd';
    protected $_parentType = 'Budget';
    protected $validAttribs = array('text' => '', '@iso_date' => '');
}
