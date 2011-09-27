<?php
/**
 *Class for handling all activity to xml generation and xml to activity generation functionality
 */
class Iati_WEP_XmlHandler
{
    protected $xml;
    
    function __construct($activities)
    {
        $this->prepareXmlWrapper();
        
        //Generate xml for each activity
        foreach($activities as $activity)
        {
            $this->generateActivityXml($activity);
        }
    }
    
    /**
     *Prepare The xml top level coantainer i.e acitvities.
     */
    public function prepareXmlWrapper()
    {
        $this->xml = new SimpleXMLElement('<iati-activities></iati-activities>');
        $this->xml->addAttribute('generated-datetime',date('Y-m-d h:m:s'));
    }
    
     /**
     *returns xml output of the activities
     */
    public function getXmlOutput()
    {
        return $this->xml->asXML();
    }
    
    /**
     *@param object $activity an activity object
     *@return xml of the activity
     */
    public function generateActivityXml($activity)
    {
        $activity_node = $this->_getXmlNode($activity,$this->xml);
        
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_REPORTING_ORG) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_IDENTIFIER) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_OTHER_ACTIVITY_IDENTIFIER) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_TITLE) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_DESCRIPTION) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_ACTIVITY_STATUS) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_ACTIVITY_DATE) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_CONTACT_INFO) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_PARTICIPATING_ORG) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_RECIPIENT_COUNTRY) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_RECIPIENT_REGION) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_LOCATION) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_SECTOR) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_POLICY_MARKER) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_COLLABORATION_TYPE) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_DEFAULT_FLOW_TYPE) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_DEFAULT_FINANCE_TYPE) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_DEFAULT_AID_TYPE) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_DEFAULT_TIED_STATUS) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_BUDGET) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_PLANNED_DISBURSEMENT) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_TRANSACTION) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_DOCUMENT_LINK) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_ACTIVITY_WEBSITE) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_RELATED_ACTIVITY) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_CONDITIONS) , $activity_node);
        $this->getElementXml( $activity->getElementsByType(Iati_Activity_Element::TYPE_RESULT) , $activity_node);
    }
    
    /**
     *@param array $element array elements whose xml is to be generated
     *@param Object $parent Simplexmlobject of the parent element 
     */
    public function getElementXml($elements,$parent = null)
    {
        foreach($elements as $element)
        {
            $element_node = $this->_getXmlNode($element,$parent);
            if($element->getElements()){
                //Get xml for element's sub elements
                foreach($element->getElements() as $sub_element)
                {
                    $sub_element_node = $this->_getXmlNode($sub_element,$element_node);
                }
            }
        }
    }
    
     /**
     *@param array $element elements whose xml is to be generated
     *@param Object $parent Simplexmlobject of the parent element
     *@return Object $element_node SimpleXmlObject of the node of the element
     */
    protected function _getXmlNode($element,$parent = null)
    {
        $element_data = $this->getElementsData($element);
        if(empty($element_data['attributes']))
        {
            return null;
        }
        $element_node = $this->_generateXml($element_data,$parent);
        
        return $element_node;
    }
    
    /**
     *Gets values for all the attributes of the element
     *@param Object $element object of the element whose xml is being prepared
     */
    protected function getElementsData($element)
    {
        $type = $element->getType();
        
        $attribs = $element->getValidAttribs();
        $attributes = array();
        $model_wep = new Model_Wep();
        foreach(array_keys($attribs) as $attrib)
        {
            if($element->getAttrib($attrib)){
                $attributes[$attrib] = $element->getAttribValue($attrib);
            }
        }
        $elements['type'] = $element->getXmlElementTag();
        $elements['attributes'] = $attributes;
        
        return $elements;
    }
    
    /**
     *Funtion to generate xml
     *@param array $element the first element is the type and the second is array of attrib value key value pair
     */
    protected function _generateXml($element,$parent = null)
    {
        $type= $element['type'];
        
        
        if(!is_object($parent)){
            $element_xml = new SimpleXMLElement("<$type>".$element['attributes']['text']."</$type>");
        } else {
            $element_xml = $parent->addChild($type,$element['attributes']['text']);
        }
        
        foreach($element['attributes'] as $attrib=>$value)
        {
            if(preg_match('/@/',$attrib))
            {
                if($attrib == "@xml_lang"){
                    $attrib = preg_replace('/_/',':',$attrib);
                }
                $name = preg_replace('/@/','',$attrib);
                $name = preg_replace('/_/','-',$name);
                $element_xml->addAttribute($name,$value);
                
            }
        }
        return $element_xml;
    }
}