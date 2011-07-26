<?php
class Iati_WEP_Activity_TitleFactory //extends Iati_WEP_Activity_BaseFactory
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


    public function factory($objectType = 'Title', $data = array())
    {
        $this->globalObject = $this->getRootNode();
        if($data){
            $this->globalObject = $this->getRootNode();
            foreach ($data as $key => $values){
                if(is_array ($values)){
                    $tree = $this->createObjects ($objectType, $this->globalObject, $values);
                }
            }
        }
        else{
            $tree = $this->createObjects ($objectType);
        }

        return $tree;
    }
    
    public function createObjects ($class, $parent = null, $values = array ())
    {
        $string = 'Iati_WEP_Activity_Elements_' . $class;
        $object = new $string ();
        $registryTree = Iati_WEP_TreeRegistry::getInstance ();

        if($values){
            $data = $this->getFields($class, $values);
            $object->setAttributes($data);
            $registryTree->addNode($object, $parent);
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
            $key_array = explode('_', $key);
            if($key_array[0] == $class){
                array_shift($key_array);
                $newArray[implode("_", $key_array)] = $value;
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
//            print_r($elementObj);
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