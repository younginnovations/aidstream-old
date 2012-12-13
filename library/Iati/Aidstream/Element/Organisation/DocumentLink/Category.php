<?php

class Iati_Aidstream_Element_Organisation_DocumentLink_Category extends Iati_Core_BaseElement
{
    protected $className = 'Category';
    protected $displayName = 'Category';
    protected $attribs = array('id' , '@code', '@xml_lang' , 'text');
    protected $iatiAttribs = array('@code', '@xml_lang' , 'text');
    protected $tableName = 'iati_organisation/document_link/category';
}