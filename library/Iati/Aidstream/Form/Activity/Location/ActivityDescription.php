<?php

class Iati_Aidstream_Form_Activity_Location_ActivityDescription extends Iati_Core_BaseForm
{  
    public function getFormDefination()
    {
        $this->setAttrib('class' , 'simplified-sub-element');

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
            
        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')
            ->setValue($this->data['text'])
            ->setAttribs(array('rows'=>'2' , 'cols'=> '20'));

        $this->addElements($form);
        return $this;
    }
}