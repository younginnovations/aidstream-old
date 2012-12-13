<?php

class Iati_Aidstream_Form_Organisation_Name extends Iati_Core_BaseForm
{  
    public function getFormDefination()
    {
        $model = new Model_Wep();
        $this->setAttrib('class' , 'simplified-sub-element');
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
            
        $form['text'] = new Zend_Form_Element_Text('text');
        $form['text']->setLabel('Name')
            ->setValue($this->data['text'])
            ->setAttribs(array('class' => 'form-text'));
            
        $lang = $model->getCodeArray('Language', null, '1' , true);
        $form['xml_lang'] = new Zend_Form_Element_Select('xml_lang');
        $form['xml_lang']->setLabel('Language')
            ->setValue($this->data['@xml_lang'])
            ->setAttrib('class' , 'form-select')
            ->addMultioptions($lang);

        $this->addElements($form);
        return $this;
    }
}