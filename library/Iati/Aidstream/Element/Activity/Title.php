<?php

class Iati_Aidstream_Element_Activity_Title extends Iati_Core_BaseElement
{  
    protected $isMultiple = true;
    protected $className = 'Title';
    protected $displayName = 'Title';
    protected $isRequired = true;
    protected $tableName = 'iati_title';
    protected $attribs = array('id','text','@xml_lang');
    protected $iatiAttribs = array('text','@xml_lang');
}