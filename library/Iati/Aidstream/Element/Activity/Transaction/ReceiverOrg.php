<?php

class Iati_Aidstream_Element_Activity_Transaction_ReceiverOrg extends Iati_Core_BaseElement
{
    protected $className = 'ReceiverOrg';
    protected $displayName = 'Receiver Organisation';
    protected $attribs = array('id' , '@ref', '@receiver_activity_id' , 'text');
    protected $iatiAttribs = array('@ref', '@receiver_activity_id' , 'text');
    protected $tableName = 'iati_transaction/receiver_org';
}