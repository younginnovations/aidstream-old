<?php

class Iati_Organisation_Form_BaseForm extends Iati_SimplifiedForm
{
    protected $element;
    protected $data;
    protected $count;
    protected $isMultiple;
    
    public function getFormDefination(){}

    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function setCount($count = 0)
    {
        $this->count = $count;
    }    
    
    public function setMultiple($multiple = false)
    {
        $this->isMultiple = $multiple;
    }
    
    public function setElement($element)
    {
        $this->element = $element;
        $this->setData($element->getData());
        $this->setMultiple($element->getIsMultiple());
        $this->setCount($element->getCount());
    }
    
    public function getForm()
    {
        if($this->isMultiple){
            $form = new Iati_Form();
            $elementForm = $this->getFormDefination();

            $childElements = $this->element->getChildElements();
            if(!empty($childElements)){
                foreach($childElements as $childElement){
                    $childElementName = get_class($this->element)."_$childElement";
                    $childElement = new $childElementName();
                    $childElement->setData($this->data);
                    $childForm = $childElement->getForm();
                    $childForm->removeDecorator('form');
                    $elementForm->addSubForm($childForm , $childElement->getClassName());
                }    
            }
            // add remove to form
            $remove = new Iati_Form_Element_Note('remove');
            $remove->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'simplified-remove-element'));
            $remove->setValue("<a href='#' class='button' value='{$this->element->getFullName()}'> Remove element</a>");
            $elementForm->addElement($remove);
                    
            $form->addSubForm($elementForm , $this->element->getClassName());
            $form->removeDecorator('form');
            
            // add add button to wrapper form;
            $add = new Iati_Form_Element_Note('add');
            $add->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'simplified-add-more'));
            $add->setValue("<a href='#' class='button' value='{$this->element->getFullName()}'> Add More</a>");
            $form->addElement($add);
            
        } else {
            $form = $this->getFormDefination();
            $childElements = $this->element->getChildElements();
            if(!empty($childElements)){
                foreach($childElements as $childElement){
                    $childElementName = get_class($this->element)."_$childElement";
                    $childElement = new $childElementName();
                    $childElement->setData($this->data);
                    $childForm = $childElement->getForm();
                    $childForm->removeDecorator('form');
                    $form->addSubForm($childForm , $childElement->getClassName());
                }    
            }
        }
        $form = $this->prepare($form);
        return $form;
    }
    
    protected function prepare($form)
    {
        $form->setIsArray(true);
        $form->setElementsBelongTo("{$this->element->getClassName()}[{$this->count}]");
        $form->addDecorators( array(
                    array( 'wrapper' => 'HtmlTag' ),
                    array( 'tag' => 'fieldset' , 'options' => array('legend' => $this->element->getDisplayName()))
                )
        );
        $form->addDecorators( array(array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'element-wrapper'))));
        return $form;
    }
    
    public function addSubmitButton($label)
    {
        if($this->submit){
            $this->removeElement('sumbit');
        }
        $this->addElement('submit' , 'save_and_view',
            array(
                'label' => 'Save and View',
                'required' => false,
                'ignore'   => false,
            )
        );
        $this->addElement('submit' , 'save',
            array(
                'label'    => $label,
                'required' => false,
                'ignore'   => false,
            )
        );
    }
}