<?php
class Form_Wep_IatiIdentifier extends App_Form
{
    public function add($state = 'add', $account_id = '')
    {
        
        $form = array();
        
        $form1 = new Form_Wep_ReportingOrganisation();
        $form1->add('add', $account_id);
        
        $form['iati_identifier_text'] = new Zend_Form_Element_Text('iati_identifier_text');
        $form['iati_identifier_text']->setLabel('Iati Identifier')->setRequired();
        
        $this->addSubForm($form1, 'Reporting Organisation');
        
        $this->addElements($form);
        $this->addDisplayGroup(array('iati_identifier_text'), 'field',array('legend'=>'Iati Identifier'));
       
        
        $save = new Zend_Form_Element_Submit('save');
        $save->setValue('Save')->setAttrib('class',$state);
        $this->addElement($save);
        $this->setMethod('post');
    }
}