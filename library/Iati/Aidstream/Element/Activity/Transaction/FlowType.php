<?php

class Iati_Aidstream_Element_Activity_Transaction_FlowType extends Iati_Core_BaseElement
{
    protected $className = 'FlowType';
    protected $displayName = 'Flow Type';
    protected $attribs = array('id' , '@code' , 'text' , '@xml_lang');
    protected $iatiAttribs = array('@code' , 'text' , '@xml_lang');
    protected $tableName = 'iati_transaction/flow_type';
}