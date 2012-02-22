<?php

class Form_Wep_PublishToRegistry extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('id' , 'publish-to-registry');
        
        $form = array();
        
        $form['file_ids'] = new Zend_Form_Element_Hidden('file_ids');
        
        $form['push_to_registry'] = new Zend_Form_Element_Button('push_to_registry');
        $form['push_to_registry']->setLabel('Publish In IATI Registry')
            ->setAttrib('class' , 'form-submit');
        
        $this->addElements($form);
    }
}