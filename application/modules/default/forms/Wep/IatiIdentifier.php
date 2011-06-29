<?php
class Form_Wep_IatiIdentifier extends App_Form
{
    public function add($state = 'add', $activity_id = '', $account_id = '')
    {
        $this->setName('iati_identifier');
        $form = array();
        
        $form['iati_identifier_text'] = new Zend_Form_Element_Text('iati_identifier_text');
        $form['iati_identifier_text']->setLabel('Identifier')->setRequired();
        
        $form['activity_id'] = new Zend_Form_Element_Hidden('activity_id');
        $form['activity_id']->setValue($activity_id)->setAttrib('class','hidden-field');
        
        $form['form_name'] = new Zend_Form_Element_Hidden('form_name');
        $form['form_name']->setValue('Iati Identifier')->setAttrib('class','hidden-field');
        
        $form['table'] = new Zend_Form_Element_Hidden('table');
        $form['table']->setValue('iati_identifier')->setAttrib('class','hidden-field');
        
        /*if($state != 'add'){
           $action =  $state;
        }
        else{
            $action = 
        }*/
        
        
        $form['save'] = new Zend_Form_Element_Submit('save');
        $form['save']->setValue('Save')->setAttrib('class',$state);
        
        $this->addElements($form);
        $this->addDisplayGroup(array('iati_identifier_text', 'save'), 'field',array('legend'=>'Iati Identifier'));
       
       // $this->addElements($form);
        $this->setMethod('post');
    }
}