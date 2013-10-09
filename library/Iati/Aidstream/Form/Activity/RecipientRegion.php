<?php

class Iati_Aidstream_Form_Activity_RecipientRegion extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']); 
        
        $countryCode = $model->getCodeArray('Region', null, '1' , true);
        $form['code'] = new Zend_Form_Element_Select('code');
        $form['code']->setLabel('Region Code')  
            ->setValue($this->data['@code'])    
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($countryCode);
            
        $vocabCode = $model->getCodeArray('RegionVocabulary', null, '1' , true);
        $form['vocabulary'] = new Zend_Form_Element_Select('vocabulary');
        $form['vocabulary']->setLabel('Region vocabulary')  
            ->setValue($this->data['@vocabulary'])    
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($vocabCode);

        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Name')  
            ->setValue($this->data['text'])
            ->setAttribs(array('rows'=>'2' , 'cols'=> '20'));
        
        $form['percentage'] = new Zend_Form_Element_Text('percentage');
        $form['percentage']->setLabel('Percentage')  
            ->setValue($this->data['@percentage'])
            ->addValidator(new App_Validate_NumericValue())
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