<?php

class Iati_Aidstream_Form_Activity_PolicyMarker extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']); 
        
        $code = $model->getCodeArray('PolicyMarker', null, '1' , true);
        $form['code'] = new Zend_Form_Element_Select('code');
        $form['code']->setLabel('Policy Marker')
            ->setRequired()
            ->setValue($this->data['@code'])
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($code);
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
       
        $policySignificance = $model->getCodeArray('PolicySignificance', null, '1' , true);
        $form['significance'] = new Zend_Form_Element_Select('significance');
        $form['significance']->setLabel('Significance')
            ->setRequired()
            ->setValue($this->data['@significance'])
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($policySignificance);
        $this->addElements($form);
        return $this;
    }
}