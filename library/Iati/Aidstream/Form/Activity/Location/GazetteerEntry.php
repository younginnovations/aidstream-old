<?php

class Iati_Aidstream_Form_Activity_Location_GazetteerEntry extends Iati_Core_BaseForm
{  
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
            
        $form['text'] = new Zend_Form_Element_Text('text');
        $form['text']->setLabel('Text')
            ->setRequired()    
            ->setValue($this->data['text'])
            ->setAttribs(array('class' => 'form-text'));
            
        $gazetteerAgency = $model->getCodeArray('GazetteerAgency', null, '1' , true);
        $form['gazetteer_ref'] = new Zend_Form_Element_Select('gazetteer_ref');
        $form['gazetteer_ref']->setLabel('Gazetteer Agency')
            ->setValue($this->data['@gazetteer_ref'])
            ->setRequired()
            ->setAttrib('class' , 'form-select')
            ->addMultioptions($gazetteerAgency);

        $this->addElements($form);
        return $this;
    }
}