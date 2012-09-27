<?php
/**
 * Base class for iati elements.
 * Coantains all the attributes of the element and the methods for functionalities that can be done
 * for the elements like creating form, saving, retrieving etc.
 *
 * @author bhabishyat
 */
class Iati_Organisation_Element_BaseElement extends Zend_Db_Table_Abstract
{
    protected $isMultiple = false;
    protected $isRequired = false;
    protected $className;
    protected $displayName;
    protected $data;
    protected $parentName;
    protected $childElements = array();
    protected $attribs = array();
    protected $iatiAttribs = array();
    protected $tableName;
    public $count;
    
    public function init()
    {
        $this->_name = $this->tableName;
    }
    
    public function setCount($count)
    {
        $this->count = $count;
    }
    
    public function getCount()
    {
        return $this->count;
    }
    
    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function getIsMultiple()
    {
        return $this->isMultiple;
    }
    
    public function getClassName()
    {
        return $this->className;
    }
    
    /**
     * Function to get the full name of the element i.e name with parent name
     * Fullname can be used to directly create the element.
     */
    public function getFullName()
    {
        $classname = get_class($this);
        $fullname = preg_replace('/Iati_Organisation_Element_/' , '' , $classname);
        return $fullname;
    }
    
    /**
     * Function to get the display name for the element.
     * If display name is present it is returned else classname is returned
     */
    public function getDisplayName()
    {
        if($this->displayName){
            return $this->displayName;
        } else {
            $this->className;
        }
    }
    
    /**
     * Function to get the child elements of the element
     */
    public function getChildElements()
    {
        return $this->childElements;
    }
    
    /**
     * Function to get the form for the element.
     * 
     * This function creates the element's own form,
     * creates child elements if present , calls getForm() of the child element and
     * adds the child form as the subform of the element's form
     *
     * @return form for the element with children's forms added as subform.
     */
    public function getForm()
    {
        $formname = preg_replace('/Element/' , 'Form' , get_class($this));
        if($this->data){
            if($this->isMultiple){
                $form = new Iati_Organisation_Form_BaseForm();
                foreach($this->data as $data){
                    $eleForm = new $formname(array('element' => $this));
                    $eleForm->setData($data);
                    $elementForm = $eleForm->getForm();
                    $childElements = $this->getChildElements();
                    if(!empty($childElements)){
                        foreach($childElements as $childElementClass){
                            $childElementName = get_class($this)."_$childElementClass";
                            $childElement = new $childElementName();
                            $childElement->setData($data[$childElementClass]);
                            $childForm = $childElement->getForm();
                            $childForm->removeDecorator('form');
                            $elementForm->addSubForm($childForm , $childElementClass.$childForm->getCount($childElementClass));
                        }
                    }
                    // add remove to form
                    $remove = new Iati_Form_Element_Note('remove');
                    $remove->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'simplified-remove-element'));
                    $remove->setValue("<a href='#' class='button' value='{$this->getFullName()}'> Remove element</a>");
                    $elementForm->addElement($remove);
                    $elementForm->removeDecorator('form');
                    $elementForm->prepare();

                    $form->addSubForm($elementForm , $this->getClassName().$elementForm->getCount($this->getClassName()));
                }

                // add add button to wrapper form;
                $add = new Iati_Form_Element_Note('add');
                $add->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'simplified-add-more'));
                $add->setValue("<a href='#' class='button' value='{$this->getFullName()}'> Add More</a>");
                $form->addElement($add);
                
            } else {
                $eleForm = new $formname(array('element' => $this));
                $form = $eleForm->getForm();
                $childElements = $this->getChildElements();
                if(!empty($childElements)){
                    foreach($childElements as $childElementClass){
                        $childElementName = get_class($this)."_$childElementClass";
                        $childElement = new $childElementName();
                        $childElement->setData($this->data[$childElementClass]);
                        $childForm = $childElement->getForm();
                        $childForm->removeDecorator('form');
                        $form->addSubForm($childForm , $childElementClass.$childForm->getCount($childElementClass));
                    }      
                }
                $form->prepare();
            }
        } else {
            if($this->isMultiple){
                $form = new Iati_Organisation_Form_BaseForm();
                $eleForm = new $formname(array('element' => $this));
                $elementForm = $eleForm->getForm();
                $childElements = $this->getChildElements();
                if(!empty($childElements)){
                    foreach($childElements as $childElementClass){
                        $childElementName = get_class($this)."_$childElementClass";
                        $childElement = new $childElementName();
                        $childForm = $childElement->getForm();
                        $childForm->removeDecorator('form');
                        $elementForm->addSubForm($childForm , $childElementClass.$childForm->getCount($childElementClass));
                    }    
                }
                 // add remove to form
                $remove = new Iati_Form_Element_Note('remove');
                $remove->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'simplified-remove-element'));
                $remove->setValue("<a href='#' class='button' value='{$this->getFullName()}'> Remove element</a>");
                $elementForm->addElement($remove);
                $elementForm->removeDecorator('form');
                $elementForm->prepare();
                
                $form->addSubForm($elementForm , $this->getClassName());
                
                // add add button to wrapper form;
                $add = new Iati_Form_Element_Note('add');
                $add->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'simplified-add-more'));
                $add->setValue("<a href='#' class='button' value='{$this->getFullName()}'> Add More</a>");
                $form->addElement($add);
                
            } else {
                $eleForm = new $formname(array('element' => $this));
                $form = $eleForm->getFormDefination();
                $childElements = $this->getChildElements();
                if(!empty($childElements)){
                    foreach($childElements as $childElementClass){
                        $childElementName = get_class($this)."_$childElementClass";
                        $childElement = new $childElementName();
                        $childForm = $childElement->getForm();
                        $childForm->removeDecorator('form');
                        $form->addSubForm($childForm , $childElementClass.$childForm->getCount($childElementClass));
                    }    
                }
                $form->prepare();
            }
        }
        $this->_wrapForm($form);
        return $form;
    }
    
    /**
     * Function to add fieldset and wrapper div to the form
     */
    protected function _wrapForm($form)
    {        
        $form->addDecorators( array(
                    array( 'wrapper' => 'HtmlTag' ),
                    array( 'tag' => 'fieldset' , 'options' => array('legend' => $this->getDisplayName()))
                )
        );
        $form->addDecorators( array(array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'element-wrapper'))));
        return $form;
    }
    
    /**
     * Function to save the element's and its childrens data.
     * 
     * This function saves the elements data into its table,
     * creates child elements if present and calls save for the child elements
     * @param $data data of the element and its childrens
     * @param $parent array of parent column as key and its value as the array value. If the element is the
     * topmost element i.e has no parent the parameter is an empty array.
     */
    public function save($data , $parent = array())
    {        
        $parentName = array_pop(array_keys($parent));
        if($this->isMultiple){
            foreach($data as $elementData){
                $elementsData = $this->getElementsData($elementData);
                if(!empty($parent)){
                    $elementsData[$parentName."_id"] = $parent[$parentName];
                }
                
                // If no id is present, insert the data else update the data using the id.
                if(!$elementsData['id']){
                    $elementsData['id'] = null;
                    $id[$this->convertCamelCaseToUnderScore($this->className)] = $this->insert($elementsData);
                } else {
                    $eleId = $elementsData['id'];
                    unset($elementsData['id']);
                    $id[$this->convertCamelCaseToUnderScore($this->className)] = $this->update($elementsData , array('id = ?' => $eleId));                
                }
                
                // If children are present create children elements and call their save function.                
                if(!empty($this->childElements)){
                    foreach($this->childElements as $childElementClass){
                        $childElementName = get_class($this)."_$childElementClass";
                        $childElement = new $childElementName();
                        $childElement->save($elementData[$childElementClass] , $id);
                    }
                }
            }
        } else {
            $elementsData = $this->getElementsData($data);
            if(!empty($parent)){
                $elementsData[$parentName."_id"] = $parent[$parentName];
            }
            
            // If no id is present, insert the data else update the data using the id.
            if(!$elementsData['id']){
                $elementsData['id'] = null;
                $id[$this->convertCamelCaseToUnderScore($this->className)] = $this->insert($elementsData);
            } else {
                $eleId = $elementsData['id'];
                unset($elementsData['id']);
                $id[$this->convertCamelCaseToUnderScore($this->className)] = $this->update($elementsData , array('id = ?' => $eleId));                
            }
            
            // If children are present create children elements and call their save function.
            if(!empty($this->childElements)){
                foreach($this->childElements as $childElementClass){
                    $childElementName = get_class($this)."_$childElementClass";
                    $childElement = new $childElementName();
                    $childElement->save($data[$childElementClass] , $id);
                }
            }
        }
        return $id[$this->convertCamelCaseToUnderScore($this->className)];
    }

    /**
     * Function to get the data for the elements attribs form the elements and its childrens data.
     */
    public function getElementsData($data)
    {
        foreach($this->attribs as $attrib){
            $elementsData[$attrib] = $data[$attrib];
        }
        return $elementsData;
    }
    
    /**
     * Function to fetch the element and its childrens' data.
     * 
     * @param $key array with columnname as key and its value as the value.
     * If the element is child or should be fetched by parent id, the key is the parent_key coulumn else it is id column.
     */    
    public function fetchData($key)
    {
        $parentName = array_pop(array_keys($key));
        $eleId = array_pop($key);
        if($this->isMultiple){
            if($parentName){
                $parentColumn = $this->convertCamelCaseToUnderScore($parentName);
                $parentColumn .= "_id";
                $data[$this->className] = $this->fetchArray(array( $parentColumn => $eleId ));
            } else {
                $data[$this->className] = $this->fetchArray(array('id' => $eleId))->toArray();
            }

            if(!empty($this->childElements)){
                foreach($this->childElements as $childElementClass){
                    $childElementName = get_class($this)."_$childElementClass";
                    $childElement = new $childElementName();
                    $data[$childElementClass] = $childElement->retrieve(array($this->className => $id));
                }
            }
        } else {
            if($parentName){
                $parentColumn = $this->convertCamelCaseToUnderScore($parentName);
                $parentColumn .= "_id";
                $select = $this->select()->where("{$parentColumn} = ?" , $eleId);
            } else {
                $select = $this->select()->where("id = ?" , $eleId);
            }
            $row = $this->fetchRow($select);
            if($row){
                $data[$this->className] = $row->toArray();
            }
            if(!empty($this->childElements)){
                foreach($this->childElements as $childElementClass){
                    $childElementName = get_class($this)."_$childElementClass";
                    $childElement = new $childElementName();
                    $data[$childElementClass] = $childElement->retrieve(array($this->className => $id));
                }
            }
        }
        return $data;
    }
    
    /**
     * Function to delete the element and its childrens.
     * 
     * @param $key array with columnname as key and its value as the value.
     * If the element is child or should be deleted by parent, the key is the parent_key coulumn else it is id column.
     */
    public function delete($key)
    {
        
    }
    
    public function convertCamelCaseToUnderScore($className)
    {
        $underscore= strtolower(preg_replace('/([^A-Z_])([A-Z])/', '$1_$2', $className));
        return $underscore;
    }
}
