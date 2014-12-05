<?php

class Iati_Aidstream_Form_Activity_OtherActivityIdentifier extends Iati_Core_BaseForm
{

    public function getFormDefination()
    {   
        $baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $model = new Model_Wep();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $form['ref'] = new Zend_Form_Element_Text('ref');
        $form['ref']->setLabel('Reference')
                ->setRequired()
                ->setValue($this->data['@ref'])
                ->setAttrib('class' , 'form-text');
        
        $otherIdentifierType = $model->getCodeArray('OtherIdentifierType', null, '1' , true);
        $form['type'] = new Zend_Form_Element_Select('type');
        $form['type']->setLabel('Type')  
            ->setValue($this->data['@type'])
            ->setRequired()    
            ->setAttrib('class', 'form-select')
            ->setMultioptions($otherIdentifierType);
  
  
        $this->addElements($form);
        return $this;

    }
}