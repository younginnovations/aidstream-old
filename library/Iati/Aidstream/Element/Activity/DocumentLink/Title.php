<?php

class Iati_Aidstream_Element_Activity_DocumentLink_Title extends Iati_Core_BaseElement
{  
    protected $isMultiple = true;
    protected $isRequired = true;
    protected $className = 'Title';
    protected $displayName = 'Title';
    protected $tableName = 'iati_document_link/title';
    protected $attribs = array('id');
    protected $childElements = array('Narrative');
}