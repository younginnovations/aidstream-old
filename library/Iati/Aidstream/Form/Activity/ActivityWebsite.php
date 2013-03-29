<?php

class Iati_Aidstream_Form_Activity_ActivityWebsite extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']); 
        
        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')  
            ->setValue($this->data['text'])
            ->setRequired()    
            ->setAttribs(array('rows'=>'2' , 'cols'=> '20'));

        $this->addElements($form);
        return $this;
    }
}