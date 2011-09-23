<?php
class Form_Wep_ActivityStatus extends App_Form
{
    public function init()
    {
        $this->setName('iati_activity_status');
        $this->setMethod('post');
        $form = array();

        $form['ids'] = new Zend_Form_Element_Hidden('ids');
        $form['status'] = new Zend_Form_Element_Hidden('status');
        $form['change'] = new Zend_Form_Element_Button('change');
        $form['change']->class = 'change-status-button';
        $this->addElements($form);
    }
}