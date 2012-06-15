<?php
/**
 * Custom form used to rendering Iati_Elements.
 *
 */
class Iati_Form extends Zend_Form
{
    protected $_defaultDisplayGroupClass = 'Iati_Form_DisplayGroup';

    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('FormElements')
                ->addDecorator('Form')
                ->addDecorators( array(array(array( 'wrapper' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'form-wrapper'))));
        }
    }

    public function render()
    {
        $requiredSuffx = '<span title="This field is required." class="form-required">*</span>';

        foreach ($this->getElements() as $element) {
            $decorator = $element->getDecorator('Label');
            if ($decorator) {
                if ($element->getLabel()) { // need to check this, since label decorator can be blank
                    $element->setLabel($element->getLabel() . "&nbsp;");
                }
                $decorator->setOption('requiredSuffix', $requiredSuffx);
                $decorator->setOption('escape', false);
            }
            if ($element->getErrors()) {
                $this->addElementClass($element, 'error');

            }

            // Add a wrapper div to all elements other than add and remove buttons.
            if($element->getName() != 'add' && $element->getName() != 'remove'){

                if($element->getName() == 'id' || ($element->getType() == 'Zend_Form_Element_Hidden' && preg_match('/_id/' , $element->getName()))){
                    $element->addDecorators(array(
	                        array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'form-item ele-id clearfix'))
	                    )
                    );
                } else if($element->getName() == 'save_and_view' || $element->getName() == 'save'){
                     $element->addDecorators(array(
	                        array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'form-item ele-submit-buttons clearfix'))
	                    )
                    );
                } else {
	                $element->addDecorators(array(
	                        array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'form-item clearfix'))
	                    )
	                );
                }
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
     * @param String $formClass  Class name of the form to be added.
     * @param Object $element        Object of the Element whose form is added
     * @param Integer $elementCount     Count value for the element. It is added as the array key for the element's array
     * @param Array $attribs        Array of form element values.
     */
    public function addSubElement($formClass, $element , $elementCount , $attribs = null )
    {
        $elementClassName = $element->getClassName();
        $subForm = new $formClass();
        $subForm->removeDecorator('form');
        $subForm->setElementsBelongTo("{$elementClassName}[{$elementCount}]");
        if($attribs){
            $subForm->populate($attribs);
        }
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

    public function validate(){
        $isValid = true;
        if(!$this->getSubForms()){
            $elementClass = preg_replace('/Form/' , 'Activity_Elements' , get_class($this));
            $element = new $elementClass();
            if($element->isRequired()){
                $values = $this->getValues();
                return $this->isValid($values);
            } else {
                $values = $this->getValues();
                $name = $this->getName();
                $itemNumber = substr($name , -1);
                $eleName = substr($name , 0 , -1) ;
                $data = $values[$eleName][$itemNumber];
                unset($data['remove']);
                unset($data['add']);
                $notEmpty = false;
                foreach($data as $key=>$value){
                    if($value && $key !== 'id'){
                        $notEmpty = true;
                    }
                }
                if($notEmpty){
                    $values = $this->getValues();
                    return $this->isValid($values);
                }
                return true;
            }
        }
        foreach($this->getSubForms() as $subform){
            if(!$subform->validate()){
                $isValid = false;
            }
        }
        foreach($this->getElements() as $element){
            if(!$element->isValid($element->getValue())){
                $isValid = false;
            }
        }
        return $isValid;
    }
}
