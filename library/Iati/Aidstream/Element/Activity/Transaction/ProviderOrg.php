<?php

class Iati_Aidstream_Element_Activity_Transaction_ProviderOrg extends Iati_Core_BaseElement
{
    protected $className = 'ProviderOrg';
    protected $displayName = 'Provider Organisation';
    protected $attribs = array('id', '@provider_activity_id', '@ref');
    protected $iatiAttribs = array('@provider_activity_id', '@ref');
    protected $tableName = 'iati_transaction/provider_org';
    protected $childElements = array('Narrative');
}