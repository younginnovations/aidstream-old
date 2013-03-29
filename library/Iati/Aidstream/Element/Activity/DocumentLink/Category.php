<?php

class Iati_Aidstream_Element_Activity_DocumentLink_Category extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'Category';
    protected $displayName = 'Category';
    protected $isRequired = true;
    protected $tableName = 'iati_document_link/category';
    protected $attribs = array('id' , '@code', '@xml_lang' , 'text');
    protected $iatiAttribs = array('@code', '@xml_lang' , 'text');    
}