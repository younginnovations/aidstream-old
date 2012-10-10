<?php
/**
 * Base class for Creating element forms, Extends Iati_SimplifiedForm
 *
 * This Class acts as the base for extending by the elements' forms. It provides basic functionalities
 * like setting data, element, preparing the form etc.
 * 
 * To create form for any element only the getFormDefination should be overridden with the element's form defination.
 * 
 * @param Object $element Object of the element for which the form is defined.
 * @param Array $data Data of the element for which the form is defined.
 * @param boolen $isMultiple true if the multiple element is present else false.
 *
 * @author bhabishyat <bhabishyat@gmail.com>
 */
abstract class Iati_Organisation_BaseForm extends Iati_SimplifiedForm
{
    protected $element;
    protected $data;
    protected $isMultiple;
    public static $count = array();
    
    abstract public function getFormDefination();

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
    
    /**
     * Function to set the element attribute of the class.
     * It also sets the data and isMultiple attribute from the element's attributes
     */
    public function setElement($element)
    {
        $this->element = $element;
        $this->setData($element->getData());
        $this->setMultiple($element->getIsMultiple());
    }
    
    /**
     * Function to fetch the form for the element.
     *
     * This function fetches the element's form defination and updates the count of the element.
     */
    public function getForm()
    {
        $form = $this->getFormDefination();
        $count = $this->count($this->element->getClassName());
        return $form;
    }
    
    /**
     * Function to add element names to the form. Uses count and element name for creating array of element.
     */
    public function prepare()
    {
        if($this->isMultiple) {
            $this->setIsArray(true);
            $this->setElementsBelongTo("{$this->element->getClassName()}[{$this->getCount($this->element->getClassName())}]");
        } else {
            $this->setElementsBelongTo("{$this->element->getClassName()}");
        }
    }
    
    /**
     * Function to add submit buttons to the form.
     *
     * Removes any submit button if present before and adds 'save' and 'save and view' buttons
     * @param String $label If the label of the save button should be different, an string should be passed.
     * @param String $saveAndViewlabel If the label of the save and view should be different, an string should be passed.
     */
    public function addSubmitButton($label , $saveAndViewlabel = 'Save and View')
    {
        if($this->submit){
            $this->removeElement('sumbit');
        }
        $this->addElement('submit' , 'save_and_view',
            array(
                'label' => $saveAndViewlabel,
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