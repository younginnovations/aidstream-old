<?php

class Iati_Aidstream_Element_Activity_Result_Indicator_Period_PeriodEnd extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'PeriodEnd';
    protected $displayName = 'PeriodEnd';
    protected $tableName = 'iati_result/indicator/period/period-end';
    protected $attribs = array('id' , '@iso-date' ,'text');
    protected $iatiAttribs = array('@iso-date' ,'text');
    
}