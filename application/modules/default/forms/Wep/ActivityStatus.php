<?php
class Form_Wep_ActivityStatus extends App_Form
{
    public function init()
    {
        $this->setName('iati_activity_status');
        $this->setMethod('post');
        $form = array();
        

        $status = Iati_Activity_Status::getStatus();        
        $form['status'] = new Zend_Form_Element_Select('status');
        $form['status']->setLabel('Change Status')
                    ->addMultiOptions($status);
                    
        $form['ids'] = new Zend_Form_Element_Hidden('ids');
        
        $this->addElements($form);
    }
}