<?php
class Iati_Activity_Element_Location_GazetteerEntry extends Iati_Activity_Element
{
    protected $_type = 'GazetteerEntry';
    protected $_parentType = 'Location';
    protected $_validAttribs = array('text' => '', '@gazetteer_ref' => '');
    
}