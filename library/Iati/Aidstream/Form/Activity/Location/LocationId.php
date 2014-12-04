<?php

class Iati_Aidstream_Form_Activity_Location_LocationId extends Iati_Core_BaseForm
{  
    public function getFormDefination()
    {
        $model = new Model_Wep();
        $this->setAttrib('class' , 'simplified-sub-element');
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
            
        $vocabulary = $model->getCodeArray('GeographicVocabulary', null, '1' , true);
        $form['vocabulary'] = new Zend_Form_Element_Select('vocabulary');
        $form['vocabulary']->setLabel('Vocabulary')
            ->setRequired()  
            ->setValue($this->data['@vocabulary'])   
            ->setAttrib('class' , 'form-select vocabulary_value')                
            ->setMultioptions($vocabulary);

        $form['code'] = new Zend_Form_Element_Text('code');
        $form['code']->setLabel('Code')  
            ->setRequired()
            ->setValue($this->data['@code'])    
            ->setAttrib('class' , 'form-text code');

        $this->addElements($form);
        return $this;
    }
}