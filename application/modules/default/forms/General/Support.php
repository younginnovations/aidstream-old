<?php 

class Form_General_Support extends App_Form
{
    public function init()
    {
        $this->setAttrib('id', 'support-form');
        
        $form['support_name'] = new Zend_Form_Element_Text('support_name');
        $form['support_name']->setLabel('Name')
            ->setRequired()
            ->addErrorMessage('Please enter your name');
       
        $form['support_email'] = new Zend_Form_Element_Text('support_email');
        $form['support_email']->setLabel('Email')
            ->setRequired()
            ->addErrorMessage('Please enter your email');
            
        $form['support_query'] = new Zend_Form_Element_Textarea('support_query');
        $form['support_query']->setLabel('Query')
            ->setRequired()
            ->setAttrib('rows','10')
            ->addErrorMessage('Please enter your query');
        
        $related = array('iati'=>'Iati','system'=>'System');
        $form['support_type'] = new Zend_Form_Element_Select('support_type');
        $form['support_type']->setLabel('Related To')
            ->setMultioptions($related);
        
        $form['support_submit'] = new Zend_Form_Element_Submit('support_submit');
        $form['support_submit']->setLabel('Send now!');
        $this->addElements($form);
    }
}