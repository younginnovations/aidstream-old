<?php

class Iati_Activity_Element
{
    const TYPE_ACTIVITY = 'Activity';
    const TYPE_ACTIVITY_BUDGETS = 'ActivityBudgets';
    const TYPE_ACTIVITY_DATE = 'ActivityDate';
    const TYPE_ACTIVITY_STATUS = 'ActivityStatus';
    const TYPE_ACTIVITY_WEBSITE = 'ActivityWebsite';
    const TYPE_BUDGET = 'Budget';
    const TYPE_COLLABORATION_TYPE = 'CollaborationType';
    const TYPE_CONDITIONS = 'Conditions';
    const TYPE_CONTACT_INFO = 'ContactInfo';
    const TYPE_DEFAULT_AID_TYPE = 'DefaultAidType';
    const TYPE_DEFAULT_FINANCE_TYPE = 'DefaultFinanceType';
    const TYPE_DEFAULT_FLOW_TYPE = 'DefaultFlowType';
    const TYPE_DEFAULT_TIED_STATUS = 'DefaultTiedStatus';
    const TYPE_DESCRIPTION = 'Description';
    const TYPE_DOCUMENT_LINK = 'DocumentLink';
    const TYPE_IATI_ACTIVITY = 'IatiActivity';
    const TYPE_IDENTIFIER = 'Identifier';
    const TYPE_LOCATION = 'Location';
    const TYPE_OTHER_ACTIVITY_IDENTIFIER = 'OtherIdentifier';
    const TYPE_PARTICIPATING_ORG = 'ParticipatingOrg';
    const TYPE_PLANNED_DISBURSEMENT = 'PlannedDisbursement';
    const TYPE_POLICY_MARKER = 'PolicyMarker';
    const TYPE_RECIPIENT_COUNTRY = 'RecipientCountry';
    const TYPE_RECIPIENT_REGION = 'RecipientRegion';
    const TYPE_RELATED_ACTIVITY = 'RelatedActivity';
    const TYPE_REPORTING_ORG = 'ReportingOrg';
    const TYPE_RESULT = 'Result';
    const TYPE_SECTOR = 'Sector';
    const TYPE_TITLE = 'Title';
    const TYPE_TRANSACTION ='Transaction';
    
    protected $_name;
    
    protected $_attribs = array();
    protected $_elements   = array();
    
    protected $_mapTypeElement = array();
    protected $_mapNameElement = array();
    
    protected $_validAttribs   = array();
    protected $_validElements  = array();
    
    protected $_elementLoader;
    
    protected $_type;
    protected $_parentType;
    
    public function __construct($name = null, $options = array())
    {
        $this->setName($name);
        $this->setAttribs($options);
    }
    
    public function getName()
    {
        return $this->_name;
    }
    
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }
    
    public function getAttrib($name)
    {
        if (!isset($this->_attribs[$name]))
            return null;
        return $this->_attribs[$name];
    }
    
    public function setAttrib($name, $value)
    {
        $this->_attribs[$name] = $value;
        return $this;
    }
    
    public function setAttribs(Array $attribs)
    {
        foreach ($attribs as $name => $value)
        {
            $this->setAttrib($name, $value);
        }
        return $this;
    }
    
    public function getAttribs()
    {
        return $this->_attribs;
    }
    
    public function getValidAttribs()
    {
        return $this->_validAttribs;
    }
    
    public function getElementLoader()
    {
        if (!isset($this->_elementLoader)) {
            $this->_elementLoader = Iati_Activity_ElementLoader::getInstance();
        }
        return $this->_elementLoader;
    }
    
    public function setElementLoader(Zend_Loader_PluginLoader $elementLoader)
    {
        $this->_elementLoader = $elementLoader;
    }
    
    public function setOptions(Array $options)
    {
        foreach ($options as $name => $value) {
            /*
             * @TODO make validation
             */
        }
    }
    
    public function createElement($type, $name = null, $options = array())
    {
        $class = $this->getElementLoader()->load($type);
        $object = new $class();
        $object->setName($name);
        $object->setOptions($options);
        return $object;
    }
    
    /**
     * @todo make sure the element is allowed to be added
     * @todo if two elements with same name is added, override previous one
     */
    public function addElement($type, $name = null, $options = array())
    {
        if (is_string($type)) {
            $element = $this->createElement($type, $name, $options);
        }
        elseif ($type instanceof Iati_Activity_Element) {
            $element = $type;
        }
        else {
            require_once('Iati/Exception.php');
            throw new Iati_Exception('type should be either string or instance of Iati_Activity_Element');
        }
        
        $name = $element->getName();
        $type = $element->getType();
        
        // make sure there does not exist an element with same name
        if (!is_null($name) && isset($this->_mapNameElement[$name])) {
            $elementIndex = $this->_mapNameElement[$name];
            $oldElement = $this->_elements[$elementIndex];
            $oldElementType = $oldElement->getType();
            
            $this->_elements[$elementIndex] = $element;
            
            // No change in MapNameElement
            // Change the MapTypeElement
            $oldElementTypeIndex = array_keys($this->_mapTypeElement[$oldElementType], $elementIndex);
            unset($this->_mapTypeElement[$oldElementType][$elementIndex]);
            
            $this->_mapTypeElement[$type][] = $elementIndex;
        }
        else {
            $this->_elements[] = $element;
            end($this->_elements);
            $elementIndex = key($this->_elements);
        
            if (!is_null($name)) {
                $this->_mapNameElement[$name] = $elementIndex;
            }
        
            if (!isset($this->_mapTypeElement[$type])) {
                $this->_mapTypeElement[$type] = array();
            }
            $this->_mapTypeElement[$type][] = $elementIndex;
        }
        return $element;
    }
    
    public function getElement($name)
    {
        if (!isset($this->_mapNameElement[$name])) {
            return null;
        }
        
        $elementIndex = $this->_mapNameElement[$name];
        return $this->_elements[$elementIndex];
    }
    
    public function getElements()
    {
        return $this->_elements;
    }
    
    public function getElementsByType($type)
    {

        $output = array();
        foreach($this->_elements as $element)
        {
            if($element->getType() == $type){
                $output[] = $element;
            }
        }
        return $output;
    }
    
    /**
     * Gets all elements of a type
     *
     * Multiple elements of same type can be added by assigning different names.
     * However, 
     *
     * @returns array
     *   All elements of a given type
     */
    public function getElementByType($type)
    {
        
    }
    
    protected $_value;
    public function setValue(String $value)
    {
    
    }
    
    public function getValue()
    {
    
    }
    
    public function getType()
    {
        return $this->_type;
    }
    
    public function getParentType()
    {
        return $this->_parentType;
    }
}
