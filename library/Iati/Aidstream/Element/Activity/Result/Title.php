<?php

class Iati_Aidstream_Element_Activity_Result_Title extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Title';
    protected $displayName = 'Title';
    protected $tableName = 'iati_result/title';
    protected $attribs = array('id');
    protected $childElements = array('Narrative');
    protected $viewScriptEnabled = true;
}