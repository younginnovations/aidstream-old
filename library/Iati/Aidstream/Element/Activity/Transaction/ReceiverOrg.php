<?php

class Iati_Aidstream_Element_Activity_Transaction_ReceiverOrg extends Iati_Core_BaseElement
{
    protected $className = 'ReceiverOrg';
    protected $displayName = 'Receiver Organisation';
    protected $attribs = array('id', '@receiver_activity_id', '@ref', 'text');
    protected $iatiAttribs = array('@receiver_activity_id', '@ref', 'text');
    protected $tableName = 'iati_transaction/receiver_org';
}