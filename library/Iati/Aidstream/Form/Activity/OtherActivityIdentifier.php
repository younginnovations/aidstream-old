<?php

class Iati_Aidstream_Form_Activity_OtherActivityIdentifier extends Iati_Core_BaseForm
{

    public function getFormDefination()
    {   
        $baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Identifier')
                ->setValue($this->data['text'])
                ->setRequired()
                ->setAttrib('cols', '40')
                ->setAttrib('rows', '2');
        
        $form['owner_name'] = new Zend_Form_Element_Text('owner_name');
        $form['owner_name']->setLabel('Owner Name')
                ->setValue($this->data['@owner_name'])
                ->setAttrib('class' , 'form-text');
        
        $form['owner_ref'] = new Zend_Form_Element_Text('owner_ref');
        $form['owner_ref']->setLabel('Organisation Identifier')
                ->setValue($this->data['@owner_ref'])
                ->setAttrib('class' , 'form-text');
                
        
        $this->addElements($form);
        return $this;

    }
}