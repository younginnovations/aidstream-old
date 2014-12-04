<?php

class Iati_Aidstream_Element_Activity_Location_Point_Pos extends Iati_Core_BaseElement
{   
    protected $className = 'Pos';
    protected $displayName = 'Position';
    protected $tableName = 'iati_location/point/pos';
    protected $attribs = array('id', '@latitude', '@longitude');
    protected $iatiAttribs = array('text');
    protected $viewScriptEnabled = true;
    protected $viewScript = 'pos.phtml';
}