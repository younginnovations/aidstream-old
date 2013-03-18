<?php

class Iati_Aidstream_Element_Activity_Result_Indicator_Period_Target extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Target';
    protected $displayName = 'Target';
    protected $tableName = 'iati_result/indicator/period/target';
    protected $attribs = array('id' , '@value');
    protected $iatiAttribs = array('@value');
    protected $childElements = array('Comment');
    protected $viewScriptEnabled = true;   
}