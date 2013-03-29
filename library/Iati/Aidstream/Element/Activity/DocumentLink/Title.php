<?php

class Iati_Aidstream_Element_Activity_DocumentLink_Title extends Iati_Core_BaseElement
{  
    protected $isMultiple = true;
    protected $className = 'Title';
    protected $displayName = 'Title';
    protected $isRequired = true;
    protected $attribs = array('id' , '@xml_lang' , 'text');
    protected $iatiAttribs = array('@xml_lang' , 'text');
    protected $tableName = 'iati_document_link/title';
}