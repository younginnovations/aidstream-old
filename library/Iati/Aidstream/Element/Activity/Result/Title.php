<?php

class Iati_Aidstream_Element_Activity_Result_Title extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'Title';
    protected $displayName = 'Title';
    protected $tableName = 'iati_result/title';
    protected $attribs = array('id' , '@xml_lang' , 'text');
    protected $iatiAttribs = array('@xml_lang' , 'text');
    protected $viewScriptEnabled = true;
}