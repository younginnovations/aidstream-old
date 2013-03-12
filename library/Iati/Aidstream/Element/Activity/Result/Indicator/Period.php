<?php

class Iati_Aidstream_Element_Activity_Result_Indicator_Period extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Period';
    protected $displayName = 'Period';
    protected $tableName = 'iati_result/indicator/period';
    protected $attribs = array('id');
    protected $childElements = array('PeriodStart','PeriodEnd','Target','Actual');
    protected $viewScriptEnabled = true;   
}