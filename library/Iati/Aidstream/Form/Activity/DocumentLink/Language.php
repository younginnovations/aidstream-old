<?php

class Iati_Aidstream_Form_Activity_DocumentLink_Language extends Iati_Core_BaseForm
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
            
        $lang = $model->getCodeArray('Language', null, '1' , true);
        $form['text'] = new Zend_Form_Element_Select('text');
        $form['text']->setLabel('Language')
            ->setValue($this->data['text'])
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($lang);

        $this->addElements($form);
        return $this;
    }
}