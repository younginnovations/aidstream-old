<?php

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
    public static $count;
    
    public function setCount($count)
    {
        self::$count = $count;
    }
    
    public function getCount()
    {
        return self::$count;
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
    
    public function getChildElements()
    {
        return $this->childElements;
    }
    
    /**
     * Function to get the form for the element.
     */
    public function getForm()
    {
        $formname = preg_replace('/Element/' , 'Form' , get_class($this));
        $eleForm = new $formname(array('element' => $this));
        $form = $eleForm->getForm();
        return $form;
    }
    
    public function save($data)
    {
        
    }
    
    public function retrieve($id)
    {
        
    }
    
    public function delete($id)
    {
        
    }
}
