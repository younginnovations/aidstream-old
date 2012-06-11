<?php
class Simplified_Form_Activity_Transaction_IncommingFund extends Simplified_Form_Activity_DefaultSubElement
{
    public function init()
    {
        parent::init();
        
        $this->getElement('amount')->setLabel('Incoming Fund Amount');
        $this->getElement('start_date')->setLabel('Incoming Fund Date');
        $this->getElement('currency')->setLabel('Incoming Fund Currency');
        $this->removeElement('end_date');
        
        $this->setElementsBelongTo('incommingFund[0]');
    }
    
}