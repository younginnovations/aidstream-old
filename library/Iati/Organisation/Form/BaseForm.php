<?php

class Iati_Organisation_Form_BaseForm extends Iati_SimplifiedForm
{
    protected $element;
    protected $data;
    public static $count = array();
    protected $isMultiple;
    
    public function getFormDefination(){}

    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function getIatiElement()
    {
        return $this->element;
    }
    
    public function count($element)
    {
        if(!self::$count[$element]){
            self::$count[$element] = 0;
        }
        $elecount = self::$count[$element];
        self::$count[$element] = self::$count[$element] + 1;
        return $elecount;
    }
    
    public function getCount($element)
    {
        $elecount = self::$count[$element];
        return $elecount;
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
    }
    
    public function getForm()
    {
        $form = $this->getFormDefination();
        $count = $this->count($this->element->getClassName());
        return $form;
    }
    
    public function prepare()
    {
        if($this->isMultiple) {
            $this->setIsArray(true);
            $this->setElementsBelongTo("{$this->element->getClassName()}[{$this->getCount($this->element->getClassName())}]");
        } else {
            $this->setElementsBelongTo("{$this->element->getClassName()}");
        }
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