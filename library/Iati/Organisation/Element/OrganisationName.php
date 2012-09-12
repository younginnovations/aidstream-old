<?php

class Iati_Organisation_Element_OrganisationName extends Iati_Organisation_Element_BaseElement
{
    //protected $isMultiple = true;
    protected $isRequired = true;
    protected $className = 'OrganisationName';
    protected $displayName = 'Organisation Name';
    protected $iatiAttribs = array('language' , 'name');
    protected $tableName = 'organisation/name';

    public function __construct()
    {
        self::$count++;
    }
}