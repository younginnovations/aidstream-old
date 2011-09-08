<?php
class Form_Wep_ActivityStatus extends App_Form
{
    public function init()
    {
        $this->setName('iati_activity_status');
        $this->setMethod('post');
        $form = array();

        $activity_states = Iati_WEP_ActivityState::getStatus();
        $states[] = 'Select State';
        foreach($activity_states as $state_key=>$state)
        {
            if(Iati_WEP_ActivityState::hasPermissionForState($state_key)){
                $states[$state_key] = $state; 
            }
        }
        $form['status'] = new Zend_Form_Element_Select('status');
        $form['status']->setLabel('Change Status')
                    ->addMultiOptions($states);
                    
        $form['ids'] = new Zend_Form_Element_Hidden('ids');
        
        $this->addElements($form);
    }
}