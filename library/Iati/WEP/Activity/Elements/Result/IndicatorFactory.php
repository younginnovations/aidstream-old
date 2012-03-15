<?php
class Iati_WEP_Activity_Elements_Result_IndicatorFactory extends Iati_WEP_Activity_ResultFactory
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


    public function factory($objectType = 'Indicator', $parent = NULL, $data = array())
    {

        $this->globalObject = $parent;
        $object = "create".$objectType;
        if($data){
            foreach($data['Indicator'] as $indicator){
                $tree = $this->createIndicator($indicator);
            }
        }
        else{
            $tree = $this->$object($data);
        }

        return $tree;
    }

    public function createIndicator($flatArray = array())
    {
        $indicator = new Iati_WEP_Activity_Elements_Result_Indicator ();
        $registryTree = Iati_WEP_TreeRegistry::getInstance ();
        if($flatArray){
            $data = $this->getFields('Result_Indicator', $flatArray);
            $indicator->setAttributes($data);
        }
        else{
            $indicator->setAttributes( $this->getInitialValues() );
        }
        $registryTree->addNode ($indicator, $this->globalObject);
        $this->createTitle ( $indicator, $flatArray);
        $this->createDescription ( $indicator, $flatArray);
        $this->createBaseline ($indicator, $flatArray);
        $this->createTarget ( $indicator, $flatArray);
        $this->createActual ( $indicator, $flatArray);
        
        return $registryTree;
    }

    public function createTitle($parent = null, $values = array())
    {
        $object = new Iati_WEP_Activity_Elements_Result_Indicator_Title ();
        $registryTree = Iati_WEP_TreeRegistry::getInstance ();
        if($values){
            foreach($values['Title'] as $title){
                $object = new Iati_WEP_Activity_Elements_Result_Indicator_Title ();
                $data = $this->getFields('Title', $title);
                $object->setAttributes($data);
                $registryTree->addNode($object, $parent);
            }
        }
        else{
            $object->setAttributes( $this->getInitialValues() );
            $registryTree->addNode ($object, $parent);
        }

        return $registryTree;
    }

    public function createDescription($parent = null, $values = array())
    {
        $object = new Iati_WEP_Activity_Elements_Result_Indicator_Description ();
        $registryTree = Iati_WEP_TreeRegistry::getInstance ();

        if($values){
            foreach($values['Description'] as $indicator){
                $object = new Iati_WEP_Activity_Elements_Result_Indicator_Description ();
                $data = $this->getFields('Description', $indicator);
                $object->setAttributes($data);
                $registryTree->addNode($object, $parent);
            }
        }
        else{
            $object->setAttributes( $this->getInitialValues() );
            $registryTree->addNode ($object, $parent);
        }

        return $registryTree;
    }

    public function createBaseline($parent = null, $values = array())
    {
        $object = new Iati_WEP_Activity_Elements_Result_Indicator_Baseline ();
        $registryTree = Iati_WEP_TreeRegistry::getInstance ();

        if($values){
            foreach($values['Baseline'] as $baseline){
                $object = new Iati_WEP_Activity_Elements_Result_Indicator_Baseline ();
                $data = $this->getFields('BaseLine', $baseline);
                $object->setAttributes($data);
                $registryTree->addNode($object, $parent);
            }
        } else {
            $object->setAttributes( $this->getInitialValues() );
            $registryTree->addNode ($object, $parent);
        }

        return $registryTree;
    }
    
    public function createTarget($parent = null, $values = array())
    {
        $object = new Iati_WEP_Activity_Elements_Result_Indicator_Target ();
        $registryTree = Iati_WEP_TreeRegistry::getInstance ();

       if($values){
            foreach($values['Target'] as $target){
                $object = new Iati_WEP_Activity_Elements_Result_Indicator_Target ();
                $data = $this->getFields('Target', $target);
                $object->setAttributes($data);
                $registryTree->addNode($object, $parent);
            }
        } else{
            $object->setAttributes( $this->getInitialValues() );
            $registryTree->addNode ($object, $parent);
        }

        return $registryTree;
    }
    
    public function createActual($parent = null, $values = array())
    {
        $object = new Iati_WEP_Activity_Elements_Result_Indicator_Actual ();
        $registryTree = Iati_WEP_TreeRegistry::getInstance ();
        if($values){
            foreach($values['Actual'] as $actual){
                $object = new Iati_WEP_Activity_Elements_Result_Indicator_Actual ();
                $data = $this->getFields('Actual', $actual);
                $object->setAttributes($data);
                $registryTree->addNode($object, $parent);
            }
        } else {
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

/*
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
*/
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