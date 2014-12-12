<?php

class Iati_Aidstream_Element_Organisation_DocumentLink_Language extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'Language';
    protected $displayName = 'Language';
    protected $attribs = array('id', '@code');
    protected $iatiAttribs = array('@code');
    protected $tableName = 'iati_organisation/document_link/language';
    
}