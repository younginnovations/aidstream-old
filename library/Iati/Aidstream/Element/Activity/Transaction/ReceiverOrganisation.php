<?php

class Iati_Aidstream_Element_Activity_Transaction_ReceiverOrganisation extends Iati_Core_BaseElement
{
    protected $className = 'ReceiverOrganisation';
    protected $displayName = 'Receiver Organisation';
    protected $attribs = array('id' , '@ref', '@receiver_activity_id' , 'text');
    protected $iatiAttribs = array('@ref', '@receiver_activity_id' , 'text');
    protected $tableName = 'iati_transaction/receiver_org';
}