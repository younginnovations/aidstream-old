<?php

class Iati_Aidstream_Form_Activity_CollaborationType extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']); 
        
        $collaborationType = $model->getCodeArray('CollaborationType', null, '1' , true);
        $form['code'] = new Zend_Form_Element_Select('code');
        $form['code']->setLabel('Collaboration Type Code')  
            ->setValue($this->data['@code'])
            ->setRequired()
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($collaborationType);

        $this->addElements($form);
        return $this;
    }
}