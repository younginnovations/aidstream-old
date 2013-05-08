<?php

class Iati_Aidstream_Form_Activity_LegacyData extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']); 
        
        $form['name'] = new Zend_Form_Element_Text('name');
        $form['name']->setLabel('Name')
            ->setRequired()
            ->setValue($this->data['@name'])
            ->setAttrib('class' , 'form-text');
        
        $form['value'] = new Zend_Form_Element_Text('value');
        $form['value']->setLabel('Value')  
            ->setRequired()
            ->setValue($this->data['@value'])
            ->setAttrib('class' , 'form-text');
            
        $form['iati_equivalent'] = new Zend_Form_Element_Text('iati_equivalent');
        $form['iati_equivalent']->setLabel('Iati Equivalent')  
            ->setValue($this->data['@iati-equivalent'])
            ->setAttrib('class' , 'form-text');
        
        $this->addElements($form);
        return $this;
    }
}