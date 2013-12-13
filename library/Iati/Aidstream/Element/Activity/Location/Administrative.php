<?php

class Iati_Aidstream_Element_Activity_Location_Administrative extends Iati_Core_BaseElement
{   
    protected $className = 'Administrative';
    protected $displayName = 'Administrative';
    protected $tableName = 'iati_location/administrative';
    protected $attribs = array('id' , '@country', '@adm1','@adm2','text');
    protected $iatiAttribs = array('@country', '@adm1','@adm2','text');
    protected $viewScriptEnabled = true;
    protected $viewScript = 'administrative.phtml';
}