<?php

class Iati_Aidstream_Form_Activity_Sector extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']); 

        $vocabulary = $model->getCodeArray('Vocabulary', null, '1' , true);
        $form['vocabulary'] = new Zend_Form_Element_Select('vocabulary');
        $form['vocabulary']->setLabel('Vocabulary')  
            ->setValue($this->data['@vocabulary'])   
            ->setAttrib('class' , 'form-select vocabulary_value')                
            ->setMultioptions($vocabulary);
        
        $sector = $model->getCodeArray('Sector', null, '1' , true);
        $form['code'] = new Zend_Form_Element_Select('code');
        $form['code']->setLabel('Sector')  
            ->setValue($this->data['@code'])    
            ->setAttrib('class' , 'form-select sector_value')   
            ->setMultioptions($sector);
        
        $form['non_dac_code'] = new Zend_Form_Element_Text('non_dac_code');
        $form['non_dac_code']->setLabel('Sector')  
            ->setValue($this->data['@code'])    
            ->setAttrib('class' , 'form-text non_dac_code');
        
        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')  
            ->setValue($this->data['text'])
            ->setAttribs(array('rows'=>'2' , 'cols'=> '20'));
        
        $form['percentage'] = new Zend_Form_Element_Text('percentage');
        $form['percentage']->setLabel('Percentage')  
            ->setValue($this->data['@percentage'])
            ->addValidator(new Zend_Validate_Int())    
            ->setAttrib('class' , 'form-text');
        
        $lang = $model->getCodeArray('Language', null, '1' , true);
        $form['xml_lang'] = new Zend_Form_Element_Select('xml_lang');
        $form['xml_lang']->setLabel('Language')
            ->setValue($this->data['@xml_lang'])
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($lang);

        $this->addElements($form);
        return $this;
    }
}