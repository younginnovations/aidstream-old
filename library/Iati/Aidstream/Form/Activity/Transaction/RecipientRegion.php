<?php

class Iati_Aidstream_Form_Activity_Transaction_RecipientRegion extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();

        $this->setAttrib('class' , 'first-child')
            ->setMethod('post')
            ->setIsArray(true);

        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $region = $model->getCodeArray('Region', null, '1' , true);
        $form['code'] = new Zend_Form_Element_Select('code');
        $form['code']->setLabel('Region Code')
            ->setValue($this->data['@code'])
            ->setRequired()
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($region);

        $regionVocabulary = $model->getCodeArray('RegionVocabulary', null, '1', true);
        $form['vocabulary'] = new Zend_Form_Element_Select('vocabulary');
        $form['vocabulary']->setLabel('Vocabulary')
            ->setValue($this->data['@vocabulary'])
            ->setAttrib('class', 'form-select')
            ->setMultioptions($regionVocabulary);

        $this->addElements($form);
        return $this;
    }
}