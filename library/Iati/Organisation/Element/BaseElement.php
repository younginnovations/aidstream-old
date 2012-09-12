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
    public $count;
    
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
        $this->wrapForm($form);
        return $form;
    }
    
    protected function wrapForm($form)
    {        
        $form->addDecorators( array(
                    array( 'wrapper' => 'HtmlTag' ),
                    array( 'tag' => 'fieldset' , 'options' => array('legend' => $this->getDisplayName()))
                )
        );
        $form->addDecorators( array(array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'element-wrapper'))));
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
