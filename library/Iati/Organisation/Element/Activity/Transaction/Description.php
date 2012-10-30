<?php

class Iati_Organisation_Element_Activity_Transaction_Description extends Iati_Organisation_BaseElement
{
    protected $className = 'Description';
    protected $displayName = 'Description';
    protected $attribs = array('id' , 'text' , '@xml_lang');
    protected $iatiAttribs = array('text' , '@xml_lang');
    protected $tableName = 'iati_transaction/description';
}