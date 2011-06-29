<?php
class Form_Wep_IatiActivities extends App_Form
{
    public function add($status = 'add')
    {
        $this->setName('iati_reporting_org');
        $form = array();
        $form['version'] = new Zend_Form_Element_Text('version');
        $form['version']->setLabel('Version')->setRequired();
        
        $form['generated_datetime'] = new Zend_Form_Element_Text('generated_datetime');
        $form['generated_datetime']->setLabel('Generated Date and Time')->setRequired();
        
        $form['save'] = new Zend_Form_Element_Submit('save');
        $form['save']->setValue('Save');
        
        /*$form['next-form'] = new Zend_Form_Element_Hidden('next-form');
        $form['next-form']->setValue('IatiActivity');*/
        
        $this->addElements($form);
        $this->setMethod('post');
    }
}