<?php

class Iati_Aidstream_Element_Activity_Result_Indicator_Description extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Description';
    protected $displayName = 'Description';
    protected $tableName = 'iati_result/indicator/description';
    protected $attribs = array('id');
    protected $childElements = array('Narrative');
    protected $viewScriptEnabled = true;    
}