<?php

class Iati_Aidstream_Element_Activity_RelatedActivity extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'RelatedActivity';
    protected $displayName = 'Related Activity';
    protected $tableName = 'iati_related_activity';
    protected $attribs = array('id', '@ref', '@type');
    protected $iatiAttribs = array('@ref', '@type');
}