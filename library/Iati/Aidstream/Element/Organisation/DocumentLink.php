<?php

class Iati_Aidstream_Element_Organisation_DocumentLink extends Iati_Core_BaseElement
{
    protected $isMultiple = true;
    protected $className = 'DocumentLink';
    protected $displayName = 'Document Link';
    protected $tableName = 'iati_organisation/document_link';
    protected $attribs = array('id' , '@url' , '@format');
    protected $iatiAttribs = array('@url' , '@format');
    protected $childElements = array('Language' , 'Category' , 'Title');
}