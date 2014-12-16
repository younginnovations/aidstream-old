<?php

class Iati_Aidstream_Element_Organisation_DocumentLink_Title extends Iati_Core_BaseElement
{
    protected $isRequired = true;
    protected $className = 'Title';
    protected $displayName = 'Title';
    protected $tableName = 'iati_organisation/document_link/title';
    protected $attribs = array('id');
    protected $childElements = array('Narrative');
    
}