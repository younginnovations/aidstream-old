<?php

class Iati_Organisation_Element_OrganisationName extends Iati_Organisation_Element_BaseElement
{
    //protected $isMultiple = true;
    protected $isRequired = true;
    protected $className = 'OrganisationName';
    protected $displayName = 'Organisation Name';
    protected $attribs = array('id' , 'name' , 'language' );
    protected $iatiAttribs = array('name' , 'language');
    protected $tableName = 'organisation/name';
}