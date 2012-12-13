<?php

class Iati_Aidstream_Element_Organisation_DocumentLink_Language extends Iati_Core_BaseElement
{
    protected $className = 'Language';
    protected $displayName = 'Language';
    protected $attribs = array('id' , 'text');
    protected $iatiAttribs = array('text');
    protected $tableName = 'iati_organisation/document_link/language';
}