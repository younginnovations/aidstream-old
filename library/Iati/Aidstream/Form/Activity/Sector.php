<?php

class Iati_Aidstream_Form_Activity_Sector extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']); 

        $vocabulary = $model->getCodeArray('SectorVocabulary', null, '1' , true);
        $form['vocabulary'] = new Zend_Form_Element_Select('vocabulary');
        $form['vocabulary']->setLabel('Vocabulary')  
            ->setValue($this->data['@vocabulary'])   
            ->setAttrib('class' , 'form-select vocabulary_value')                
            ->setMultioptions($vocabulary);
        
        $sector = $model->getCodeArray('Sector', null, '1' , true);
        $form['code'] = new Zend_Form_Element_Select('code');
        $form['code']->setLabel('Sector')
            ->setRequired()  
            ->setValue($this->data['@code'])    
            ->setAttrib('class' , 'form-select sector_value')   
            ->setMultioptions($sector);
        
        $form['non_dac_code'] = new Zend_Form_Element_Text('non_dac_code');
        $form['non_dac_code']->setLabel('Sector') 
            ->setRequired() 
            ->setValue($this->data['@code'])    
            ->setAttrib('class' , 'form-text non_dac_code');
        
        $form['percentage'] = new Zend_Form_Element_Text('percentage');
        $form['percentage']->setLabel('Percentage')  
            ->setValue($this->data['@percentage'])
            ->addValidator(new Zend_Validate_Int())    
            ->setAttrib('class' , 'form-text');        

        $this->addElements($form);
        return $this;
    }
}