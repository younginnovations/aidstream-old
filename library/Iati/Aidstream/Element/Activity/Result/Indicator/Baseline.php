<?php

class Iati_Aidstream_Element_Activity_Result_Indicator_Baseline extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Baseline';
    protected $displayName = 'Baseline';
    protected $tableName = 'iati_result/indicator/baseline';
    protected $attribs = array('id' , '@year' ,'@value');
    protected $iatiAttribs = array('@year' ,'@value');
    protected $childElements = array('Comment');
    protected $viewScriptEnabled = true;
}