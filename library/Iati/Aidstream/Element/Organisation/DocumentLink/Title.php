<?php

class Iati_Aidstream_Element_Organisation_DocumentLink_Title extends Iati_Core_BaseElement
{
    protected $className = 'Title';
    protected $displayName = 'Title';
    protected $isRequired = true;
    protected $attribs = array('id' , '@xml_lang' , 'text');
    protected $iatiAttribs = array('@xml_lang' , 'text');
    protected $tableName = 'iati_organisation/document_link/title';
}