<?php

class Iati_Aidstream_Element_Activity_Result_Indicator_Baseline_Comment extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Comment';
    protected $displayName = 'Comment';
    protected $tableName = 'iati_result/indicator/baseline/comment';
    protected $attribs = array('id');
    protected $childElements = array('Narrative');
    protected $viewScriptEnabled = true;
}