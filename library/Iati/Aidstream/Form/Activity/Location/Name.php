<?php

class Iati_Aidstream_Form_Activity_Location_Name extends Iati_Core_BaseForm
{  
    public function getFormDefination()
    {
        $model = new Model_Wep();
        $this->setAttrib('class' , 'simplified-sub-element');
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
            
        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')
            ->setRequired()    
            ->setValue($this->data['text'])
            ->setAttribs(array('rows'=>'2' , 'cols'=> '20'));
            
        $lang = $model->getCodeArray('Language', null, '1' , true);
        $form['xml_lang'] = new Zend_Form_Element_Select('xml_lang');
        $form['xml_lang']->setLabel('Language')
            ->setValue($this->data['@xml_lang'])
            ->setAttrib('class' , 'form-select')
            ->addMultioptions($lang);

        $this->addElements($form);
        return $this;
    }
}