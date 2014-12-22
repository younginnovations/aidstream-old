<?php

class Iati_Aidstream_Element_Activity_Title extends Iati_Core_BaseElement
{  
    protected $className = 'Title';
    protected $displayName = 'Title';
    protected $tableName = 'iati_title';
    protected $attribs = array('id');
    protected $childElements = array('Narrative');
}