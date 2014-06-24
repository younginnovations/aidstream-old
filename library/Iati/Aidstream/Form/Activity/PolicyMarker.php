<?php

class Iati_Aidstream_Form_Activity_PolicyMarker extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']); 
        
        $policySignificance = $model->getCodeArray('PolicySignificance', null, '1' , true);
        $form['significance'] = new Zend_Form_Element_Select('significance');
        $form['significance']->setLabel('Significance')  
            ->setValue($this->data['@significance'])   
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($policySignificance);

        /**
         *  Hide Vocabulary (v1.2.7)
         *  
            $vocabulary = $model->getCodeArray('Vocabulary', null, '1' , true);
            $form['vocabulary'] = new Zend_Form_Element_Select('vocabulary');
            $form['vocabulary']->setLabel('Vocabulary')  
                ->setValue($this->data['@vocabulary'])   
                ->setAttrib('class' , 'form-select')
                ->setMultioptions($vocabulary);
        */
       
        $code = $model->getCodeArray('PolicyMarker', null, '1' , true);
        $form['code'] = new Zend_Form_Element_Select('code');
        $form['code']->setLabel('Policy Marker')  
            ->setValue($this->data['@code'])    
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($code);
        
        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Description')  
            ->setValue($this->data['text'])
            ->setAttribs(array('rows'=>'2' , 'cols'=> '20'));
        
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