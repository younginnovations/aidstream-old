<?php

class Iati_Aidstream_Form_Activity_IatiIdentifier extends Iati_Core_BaseForm
{

    public function getFormDefination()
    {   
        $baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Activity Identifier')
                ->setValue($this->data['text'])
                ->setRequired()
                ->setAttrib('cols', '40')
                ->setAttrib('rows', '2');
        
        $form['activity_identifier'] = new Zend_Form_Element_Textarea('activity_identifier');
        $form['activity_identifier']->setLabel('IATI Activity Identifier')
                ->setValue($this->data['activity_identifier'])
                ->setRequired()
                ->setAttrib('cols', '40')
                ->setAttrib('rows', '2')
                ->setAttribs(array('disabled' => 'disabled'));
                
        
        $this->addElements($form);
        return $this;

    }
}