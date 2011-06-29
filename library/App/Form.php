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
}