<?php

class Iati_Aidstream_Form_Activity_CountryBudgetItems extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $budgetVocab = $model->getCodeArray('BudgetIdentifierVocabulary', null, '1' , true);
        $form['vocabulary'] = new Zend_Form_Element_Select('vocabulary');
        $form['vocabulary']->setLabel('Vocabulary')  
            ->setValue($this->data['@vocabulary'])
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($budgetVocab);

        $this->addElements($form);
        return $this;
    }
}