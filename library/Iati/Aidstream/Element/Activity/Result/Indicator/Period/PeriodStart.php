<?php

class Iati_Aidstream_Element_Activity_Result_Indicator_Period_PeriodStart extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'PeriodStart';
    protected $displayName = 'PeriodStart';
    protected $tableName = 'iati_result/indicator/period/period-start';
    protected $attribs = array('id', '@iso_date');
    protected $iatiAttribs = array('@iso_date');
    protected $viewScriptEnabled = true;  
}