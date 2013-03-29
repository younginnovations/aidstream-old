<?php

class Iati_Aidstream_Form_Activity_DefaultTiedStatus extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']); 
        
        $tiedStatusCode = $model->getCodeArray('TiedStatus', null, '1' , true);
        $form['code'] = new Zend_Form_Element_Select('code');
        $form['code']->setLabel('Tied Status Code')  
            ->setValue($this->data['@code']) 
            ->setRequired()    
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($tiedStatusCode);

        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')  
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