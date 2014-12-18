<?php

class Iati_Aidstream_Element_Activity_Location_Point_Pos extends Iati_Core_BaseElement
{   
    protected $className = 'Pos';
    protected $displayName = 'Position';
    protected $tableName = 'iati_location/point/pos';
    protected $attribs = array('id', '@latitude', '@longitude');
    protected $iatiAttribs = array('@latitude', '@longitude');
    protected $viewScriptEnabled = true;
    protected $viewScript = 'pos.phtml';

    protected function generateElementXml($elementData, $parent) {
        $eleName = $this->getXmlName();
        $data = $this->getElementsIatiData($elementData, true);

        if(!$this->hasData($data) && empty($this->childElements)) return;  //Donot generate xml if no iati data and no child
        
        // Lat Lng as single string
        if ($data['@latitude'] != "" && $data['@longitude'] != "") {
            $coordinates = $data['@latitude'] . ' ' . $data['@longitude'];
        } else {
            return;
        }

        $xmlObj = $parent->addChild($eleName, $coordinates);
        
        return $xmlObj;
    }
}