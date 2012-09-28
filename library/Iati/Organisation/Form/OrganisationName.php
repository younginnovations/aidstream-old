<?php

class Iati_Organisation_Form_OrganisationName extends Iati_Organisation_BaseForm
{
    public function getFormDefination()
    {
        parent::init();
        $this->setAttrib('class' , 'simplified-sub-element')
            ->setIsArray(true);
            
        $model = new Model_Wep();
        $form = array();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        

        $form['name'] = new Zend_Form_Element_Text('name');
        $form['name']->setLabel('Name')
            ->setRequired()
            ->setValue($this->data['name'])
            ->setAttrib('class', 'form-text');
            
        $form['xml_lang'] = new Zend_Form_Element_Select('xml_lang');
        $form['xml_lang']->setLabel('Language')
            ->addMultiOptions($language)
            ->setValue($this->data['xml_lang'])
            ->setAttrib('class', 'form-select');


        $this->addElements($form);
        //$this->prepare();
        return $this;
    }
}