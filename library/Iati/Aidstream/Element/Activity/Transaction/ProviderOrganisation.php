<?php

class Iati_Aidstream_Element_Activity_Transaction_ProviderOrganisation extends Iati_Core_BaseElement
{
    protected $className = 'ProviderOrganisation';
    protected $displayName = 'Provider Organisation';
    protected $attribs = array('id' , '@ref', '@provider_activity_id' , 'text');
    protected $iatiAttribs = array('@ref', '@provider_activity_id' , 'text');
    protected $tableName = 'iati_transaction/provider_org';
}