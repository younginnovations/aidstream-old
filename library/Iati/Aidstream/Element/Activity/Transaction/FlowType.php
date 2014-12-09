<?php

class Iati_Aidstream_Element_Activity_Transaction_FlowType extends Iati_Core_BaseElement
{
    protected $className = 'FlowType';
    protected $displayName = 'Flow Type';
    protected $attribs = array('id' , '@code');
    protected $iatiAttribs = array('@code');
    protected $tableName = 'iati_transaction/flow_type';
}