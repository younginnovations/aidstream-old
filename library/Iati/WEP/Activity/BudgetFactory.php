<?php
class Iati_WEP_Activity_BudgetFactory extends Iati_WEP_Activity_BaseFactory
{
    public function __construct()
    {
        parent :: __construct();
    }

    public function factory($objectType = 'Budget', $data = array())
    {
        $this->globalObject = $this->getRootNode();

        if($data){
            $this->globalObject = $this->getRootNode();
            foreach($data as $key => $values){
                if(is_array($values)){
                    $tree = $this->createObjects ($objectType, null, $values);
                }
            }
        }
        else{
            $tree = $this->createObjects ($objectType);
        }

        return $tree;
    }

    public function createTransaction($flatArray = array())
    {
        $budget = new Iati_WEP_Activity_Elements_Budget ();
        $registryTree = Iati_WEP_TreeRegistry::getInstance ();
        if($flatArray){
            $data = $this->getFields('Budget', $flatArray);
            $budget->setAttributes($data);
        }
        else{
            $budget->setAttributes( $this->getInitialValues() );
        }
        $registryTree->addNode ($budget, $this->globalObject);
        
        $this->createObjects ( 'PeriodStart', $budget, $flatArray);
        $this->createObjects ( 'PeriodEnd', $budget, $flatArray);
        $this->createObjects ('Value',  $budget, $flatArray);
        
        return $registryTree;

    }

    public function createObjects($class, $parent = null, $values = array())
    {
        if($class == 'Budget'){
            return $this->createTransaction($values);
        }

        $string = 'Iati_WEP_Activity_Elements_Budget_' . $class;
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
            if(is_array($value)){
                foreach($value as $k => $v){
                $key_array = explode('_', $k);
                    if($key_array[0] == $class){
                        array_shift($key_array);
                        $newArray[implode("_", $key_array)] = $v;
                    }
                }
            }
            else{
                $key_array = explode('_', $key);
                if($key_array[0] == $class){
                    array_shift($key_array);
                    $newArray[implode("_", $key_array)] = $value;
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
    
   
    public function extractData($elementTree, $activity_id)
    {
        $registryTree = Iati_WEP_TreeRegistry::getInstance();
        $activity = new Iati_WEP_Activity_Elements_Activity();
        $activity->setAttributes(array('activity_id', $activity_id));
        $registryTree->addNode($activity);
        $elementArray = $elementTree->getElements();
        foreach($elementArray as $eachElement){
            
            $className =  'Iati_WEP_Activity_Elements_' . $eachElement->getType();
            
            $object = new $className;
            $object->setAttributes($eachElement->getAttribs());
            $registryTree->addNode($object, $activity);
            if($eachElement->getElements()){
                $children = $eachElement->getElements();
                $parent = $eachElement->getType();
                foreach($children as $eachChild){
                    $className1 = 'Iati_WEP_Activity_Elements_' . $parent . "_" . $eachChild->getType();

                    $object1 = new $className1;
                    $object1->setAttributes($eachChild->getAttribs());
                    $registryTree->addNode($object1, $object);
                    
                    if($eachChild->getElements()){
                        $subChildren = $eachChild->getElements();
                        $parent = $eachChild->getType();
                        foreach($subChildren as $eachSubChild){
                            $className1 = 'Iati_WEP_Activity_Elements_' . $parent . "_" . $eachSubChild->getType();
                            $object2 = new $className1;
                            $object2->setAttributes($eachChild->getAttribs());
                            $registryTree->addNode($object2, $object1);
                        }
                    }
                }
            }
        }
    }
    
    
}