<?php
class Iati_WEP_Activity_ContactInfoFactory
{
    protected $defaultValues = array();
    protected $globalObject;
    protected $initial;
    protected $data = array();
    protected $hasError = false;


    public function __construct()
    {
    }

    public function hasError()
    {
        return $this->hasError;
    }


    public function factory($objectType = 'ContactInfo', $data = array())
    {

        $this->globalObject = $this->getRootNode();

        $function = 'create' . $objectType;
        if($data){
            $this->globalObject = $this->getRootNode();
            foreach($data as $key => $values){
                if(is_array($values)){
                    $tree = $this->$function ($data);
                }
            }
        }
        else{
            $tree = $this->$function ($data);
        }

        return $tree;
    }

    public function createContactInfo($flatArray = array())
    {
        $contactInfo = new Iati_WEP_Activity_Elements_ContactInfo ();
        $registryTree = Iati_WEP_TreeRegistry::getInstance ();
        if($flatArray){
            $data = $this->getFields('ContactInfo', $flatArray);
            $contactInfo->setAttributes($data);
        }
        else{
            $contactInfo->setAttributes( $this->getInitialValues() );
        }
        $registryTree->addNode ($contactInfo, $this->globalObject);
        $this->createOrganisation ( $contactInfo, $flatArray);
        $this->createPersonName ( $contactInfo, $flatArray);
        $this->createTelephone ($contactInfo, $flatArray);
        $this->createEmail ( $contactInfo, $flatArray);
        $this->createMailingAddress ( $contactInfo, $flatArray);
        return $registryTree;

    }

    public function createOrganisation($parent = null, $values = array())
    {
        $object = new Iati_WEP_Activity_Elements_ContactInfo_Organisation ();
        $registryTree = Iati_WEP_TreeRegistry::getInstance ();

        if($values){
            $data = $this->getFields('Organisation', $values);
            $object->setAttributes($data);
            $registryTree->addNode($object, $parent);
        }
        else{
            $object->setAttributes( $this->getInitialValues() );
            $registryTree->addNode ($object, $parent);
        }

        return $registryTree;
    }

    public function createPersonName($parent = null, $values = array())
    {
        $object = new Iati_WEP_Activity_Elements_ContactInfo_PersonName ();
        $registryTree = Iati_WEP_TreeRegistry::getInstance ();

        if($values){
            $data = $this->getFields('PersonName', $values);
            $object->setAttributes($data);
            $registryTree->addNode($object, $parent);
        }
        else{
            $object->setAttributes( $this->getInitialValues() );
            $registryTree->addNode ($object, $parent);
        }

        return $registryTree;
    }

    public function createTelephone($parent = null, $values = array())
    {
        $object = new Iati_WEP_Activity_Elements_ContactInfo_Telephone ();
        $registryTree = Iati_WEP_TreeRegistry::getInstance ();

        if($values){
            foreach($values as $k => $v)
            {
                if(is_array($v)){
                    $object = new Iati_WEP_Activity_Elements_ContactInfo_Telephone ();
                    $data = $this->getFields('Telephone', $v);
                    $object->setAttributes($data);
                    $registryTree->addNode($object, $parent);
                }

            }

        }
        else{
            $object->setAttributes( $this->getInitialValues() );
            $registryTree->addNode ($object, $parent);
        }

        return $registryTree;
    }
    
    public function createEmail($parent = null, $values = array())
    {
        $object = new Iati_WEP_Activity_Elements_ContactInfo_Email ();
        $registryTree = Iati_WEP_TreeRegistry::getInstance ();

        if($values){
            foreach($values as $k => $v)
            {
                if(is_array($v)){
                    $object = new Iati_WEP_Activity_Elements_ContactInfo_Email ();
                    $data = $this->getFields('Email', $v);
                    $object->setAttributes($data);
                    $registryTree->addNode($object, $parent);
                }

            }

        }
        else{
            $object->setAttributes( $this->getInitialValues() );
            $registryTree->addNode ($object, $parent);
        }

        return $registryTree;
    }
    
    public function createMailingAddress($parent = null, $values = array())
    {
        $object = new Iati_WEP_Activity_Elements_ContactInfo_MailingAddress ();
        $registryTree = Iati_WEP_TreeRegistry::getInstance ();

        if($values){
            foreach($values as $k => $v)
            {
                if(is_array($v)){
                    $object = new Iati_WEP_Activity_Elements_ContactInfo_MailingAddress ();
                    $data = $this->getFields('MailingAddress', $v);
                    $object->setAttributes($data);
                    $registryTree->addNode($object, $parent);
                }

            }

        }
        else{
            $object->setAttributes( $this->getInitialValues() );
            $registryTree->addNode ($object, $parent);
        }

        return $registryTree;
    }

    public function setInitialValues($initial)
    {
        $this->defaultValues = $initial;
    }

    /**
     * default field values passed from the controller
     * @return unknown_type
     */
    public function getInitialValues()
    {
        return $this->defaultValues;
    }

    public function getRootNode()
    {
        $registry = Iati_WEP_TreeRegistry::getInstance();
        return $registry->getRootNode();
    }

    public function getFields($class, $data)
    {
        $newArray = array();

        foreach($data as $key => $value){
            if(is_array($value)){
                foreach($value as $k => $v){

                    $key_array = explode('_', $k);
                    if($key_array[0] == $class){
                        array_shift($key_array);
                        $newArray[implode("_", $key_array)] = $v;
                    }
                }
            }
        }
        return $newArray;
    }

    /**
     * recursive validation function
     * @param $obj
     * @return unknown_type
     */
    public function validateAll($obj)
    {
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        $obj->validate();
        if($obj->hasErrors()){
            $this->hasError = true;
        }

        if($registryTree->getChildNodes($obj) != NULL){
            foreach($registryTree->getChildNodes($obj) as $child){
                $this->validateAll($child);
            }
        }

    }

    // recursive function
    public function cleanData($obj, $elementObj = NULL)
    {
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        if($registryTree->getChildNodes ($obj) != NULL){
            if(get_class($obj) != 'Iati_WEP_Activity_Elements_Activity'){
                $classname = 'Iati_Activity_Element_' .
                str_replace('Iati_WEP_Activity_Elements_', "", get_class($obj));
                $element = new $classname ();
                $data = $obj->getCleanedData();
                $element->setAttribs($data);

                $elementObj->addElement($element);
                $elementObj = $element;
            }
            foreach($registryTree->getChildNodes ($obj) as $child){

                $this->cleanData( $child, $elementObj);
            }

        }
        else{
            $classname = 'Iati_Activity_Element_' .
            str_replace('Iati_WEP_Activity_Elements_', "", get_class($obj));
            $element = new $classname ();
            $data = $obj->getCleanedData();
            $element->setAttribs($data);
            $dbwrapper = new Iati_WEP_Activity_DbWrapper ($element);
            $dbwrapper->setPrimary($data['id']);
            $elementObj->addElement($element);
        }

        return $elementObj;
    }
}