<?php

class Iati_Aidstream_Element_Activity_Result_Indicator_Period_Actual extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Actual';
    protected $displayName = 'Actual';
    protected $tableName = 'iati_result/indicator/period/actual';
    protected $attribs = array('id' , '@value');
    protected $iatiAttribs = array('@value');
    protected $childElements = array('Comment');
    protected $viewScriptEnabled = true;
}