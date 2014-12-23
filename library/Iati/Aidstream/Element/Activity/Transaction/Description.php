<?php
class Iati_Aidstream_Element_Activity_Transaction_Description extends Iati_Core_BaseElement
{
    protected $className = 'Description';
    protected $displayName = 'Description';
    protected $tableName = 'iati_transaction/description';
    protected $attribs = array('id');
    protected $childElements = array('Narrative');  
}