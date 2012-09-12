<?php

class Iati_Organisation_Form_AnnualPlanningBudget extends Iati_Organisation_Form_BaseForm
{  
    public function getFormDefination()
    {
        $this->setAttrib('class' , 'simplified-sub-element');
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);

        $this->addElements($form);
        return $this;
    }
}