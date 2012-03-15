<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Form
 *
 * @author Bibek Shrestha <bibekshrestha@gmail.com>
 */
class App_Form extends Zend_Form
{
    public function render()
    {
        $requiredSuffx = '<span title="This field is required." class="form-required">*</span>';
        
        foreach ($this->getElements() as $element) {
            $decorator = $element->getDecorator('Label');
            if ($decorator) {
                if ($element->getLabel()) { // need to check this, since label decorator can be blank
                    $element->setLabel($element->getLabel() . ":&nbsp;");
                }
                $decorator->setOption('requiredSuffix', $requiredSuffx);
                $decorator->setOption('escape', false);
            }
            if ($element->getErrors()) {
                $this->addElementClass($element, 'error');
                
            }
        }

        $output = parent::render();
        return $output;
    }

    public function addElementClass(&$element, $className, $decorator = null)
    {
        if (!$element instanceof Zend_Form_Element) {
            $element = $this->getElement($element);
        }

        if ($decorator) {
            $decoratorObj = $element->getDecorator($decorator);
            $origClass = $decoratorObj->getOption('class');
            $newClass = $origClass . ' ' . $className;
            $decoratorObj->setOption('class', $newClass);
        } else {
            $origClass = $element->getAttrib('class');
            $newClass = $origClass . ' ' . $className;
            $element->setAttrib('class', $newClass);
        }

        return $this;
    }
    
    /**
     * Function to add Sub Elements form to the element.
     *
     * @param string $formClass  Class name of the form to be added.
     * @param string $elementClassName        Class name of the element whose form is added, eg for transaction _tiedstatus the
     * classname is TiedStatus.
     * @param int $elementCount     Count value for the element. It is added as the array key for the element's array
     * @param array $attribs        Array of form element values. 
     */
    public function addSubElement($formClass, $elementClassName , $elementCount , $attribs = null , $multiple = false)
    {
        $subForm = new $formClass();
        $camelCaseToSeperator = new Zend_Filter_Word_CamelCaseToSeparator(" ");
        $title = $camelCaseToSeperator->filter($elementClassName);
        $subForm->removeDecorator('form');
        if($multiple){
            $add = new Iati_Form_Element_Note('add');
            $add->addDecorator('HtmlTag', array('tag' => 'div' , 'class' => 'button'));
            $add->setValue("<a href='#' class='add-element' value='$elementClassName'> Add More</a>");
            $add->setOrder(101);
            
            $remove = new Iati_Form_Element_Note('remove');
            $remove->setValue("<a href='/wep/remove-elements?classname=$elementClassName' class='remove-this'> Remove This</a>");
            $remove->addDecorator('HtmlTag', array('tag' => 'div' , 'class' => 'remove button'));
            $remove->setOrder(100);
            $subForm->addElements(array($add , $remove));
        }
        $subForm->addDisplayGroup(array_keys($subForm->getElements()) , $elementClassName , array('legend' => $title ,'class' => $subForm->getAttrib('class')));
        $subForm->setElementsBelongTo("{$elementClassName}[{$elementCount}]");
        if($attribs){
            $subForm->populate($attribs);
        }
        $subForm->addDecorators( array(array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','options' => array('class'=>'element-wrapper')))));
        $this->addSubForm($subForm,"{$elementClassName}{$elementCount}");
        
        return $subForm;
    }
    
    /**
     * Function to add submit button to the form
     *
     * Since forms are dynamically created, elements may be added to the form at any time. So this function is
     * used to add a submit button at the end of the form before rendering the form.
     * @param string @label Label to be displayed for the submit button.
     */
    public function addSubmitButton($label)
    {
        if($this->submit){
            $this->removeElement('sumbit');
        }
        $this->addElement('submit' , 'save',
            array(
                'label'    => $label,
                'required' => false,
                'ignore'   => false,
            )
        );
    }
}