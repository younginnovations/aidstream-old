<?php

class Iati_Aidstream_Element_Activity_Sector extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'Sector';
    protected $displayName = 'Sector';
    protected $tableName = 'iati_sector';
    protected $attribs = array('id','text','@xml_lang','@code','@percentage','@vocabulary');
    protected $iatiAttribs = array('text','@xml_lang','@code','@percentage','@vocabulary');
}