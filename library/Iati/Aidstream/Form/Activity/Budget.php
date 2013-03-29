<?php

class Iati_Aidstream_Form_Activity_Budget extends Iati_Core_BaseForm
{  
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form = array();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $budgetType = $model->getCodeArray('BudgetType', null, '1' , true);
        $form['type'] = new Zend_Form_Element_Select('type');
        $form['type']->setLabel('Budget type')  
            ->setValue($this->data['@type'])
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($budgetType);

        $this->addElements($form);
        return $this;
    }
}