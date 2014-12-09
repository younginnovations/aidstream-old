<?php

class Iati_Aidstream_Element_Organisation_DocumentLink_Category extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'Category';
    protected $displayName = 'Category';
    protected $attribs = array('id' , '@code');
    protected $iatiAttribs = array('@code');
    protected $tableName = 'iati_organisation/document_link/category';
}