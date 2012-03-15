<?php

class Iati_WEP_Form_Transaction_ReceiverOrg extends Iati_Form
{
    public function init()
    {
        $this->setAttrib('class' , 'first-child')
            ->setMethod('post')
            ->setIsArray(true);
            
        $form = array();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
                    
        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')
            ->setAttribs(array('rows'=>'3' , 'cols'=> '20'));
        
        $form['ref'] = new Zend_Form_Element_Text('ref');
        $form['ref']->setLabel('Organisation Identifier Code')
            ->setAttrib('class' , 'form-text');
        
        $form['receiver_activity_id'] = new Zend_Form_Element_Text('receiver_activity_id');
        $form['receiver_activity_id']->setLabel('Reciever Activity Id')
            ->setAttrib('class' , 'form-text');        
        $this->addElements($form);
    }
}