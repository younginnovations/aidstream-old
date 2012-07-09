<?php
/**
 * Custom form used to rendering Iati_Elements.
 *
 */
class Iati_SimplifiedForm extends Zend_Form
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
            } else {
                $element->removeDecorator('label');
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
     * Custom validation form.
     * If form has no subform i.e lowest level form, check if form is empty if not then only validate the form
     *  If form has subforms first validate each subform(Subform of each type is wrapped by a wrapper)
     *  Then validate each element.
     */
    public function validate(){
        $isValid = true;
        
        if(!$this->getSubForms()){
            // Get values for the subforms.(get form name and array index to get the data)
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
        
        //Validate subforms if any
        if($this->getSubForms()){
            foreach($this->getSubForms() as $wrapperForm){
                foreach($wrapperForm->getSubForms() as $subForm){
                    if(!$subForm->validate()){
                        $isValid = false;
                    }
                }
            }
        }
        
        //validate elements
        foreach($this->getElements() as $element){
            if(!$element->isValid($element->getValue())){
                $isValid = false;
            }
        }
        return $isValid;
    }
}
