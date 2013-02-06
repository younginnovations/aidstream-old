<?php

class Iati_Aidstream_Element_Activity_Result extends Iati_Core_BaseElement
{   
    protected $isMultiple = true;
    protected $className = 'Result';
    protected $displayName = 'Result';
    protected $isRequired = true;
    protected $tableName = 'iati_result';
    protected $attribs = array('id' , '@type' , '@aggregation_status');
    protected $iatiAttribs = array('@type' , '@aggregation_status');
    protected $childElements = array(
                                     'Title' , 
                                     'Description' ,
                                     'Indicator'
                               );
    
}