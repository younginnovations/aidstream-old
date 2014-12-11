<?php

class Iati_Aidstream_Element_Activity_Result_Indicator extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Indicator';
    protected $displayName = 'Indicator';
    protected $tableName = 'iati_result/indicator';
    protected $attribs = array('id', '@measure', '@ascending');
    protected $iatiAttribs = array('@measure', '@ascending');
    protected $childElements = array('Title', 'Description', 'Baseline', 'Period');
    protected $viewScriptEnabled = true;    
}