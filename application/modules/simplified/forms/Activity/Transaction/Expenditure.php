<?php
class Simplified_Form_Activity_Transaction_Expenditure extends Simplified_Form_Activity_DefaultSubElement
{
    public function init()
    {
        parent::init();
        
        $this->getElement('amount')->setLabel('Expenditure Amount');
        $this->getElement('start_date')->setLabel('Expenditure Date');
        $this->getElement('currency')->setLabel('Expenditure Currency');
        $this->removeElement('end_date');
        
        $this->setElementsBelongTo('expenditure[0]');
    }
    
}