<?php

class Iati_Aidstream_Form_Activity_Description extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $descriptionType = $model->getCodeArray('DescriptionType', null, '1' , true);
        $form['type'] = new Zend_Form_Element_Select('type');
        $form['type']->setLabel('Description Type')  
            ->setValue($this->data['@type'])
            ->setAttrib('class' , 'form-select description-type')
            ->setMultioptions($descriptionType);
            
        $this->addElements($form);
        return $this;
    }
}