<?php
class Simplified_Form_Activity_Transaction_Commitment extends Simplified_Form_Activity_DefaultSubElement
{
    public function init()
    {
        parent::init();
        
        $this->getElement('amount')->setLabel('Commited Amount');
        $this->getElement('start_date')->setLabel('Commited Date');
        $this->getElement('currency')->setLabel('Commited Currency');
        $this->removeElement('end_date');
        
        $this->setElementsBelongTo('commitment[0]');
    }
    
}