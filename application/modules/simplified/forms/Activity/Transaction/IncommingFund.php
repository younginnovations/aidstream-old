<?php
class Simplified_Form_Activity_Transaction_IncommingFund extends Simplified_Form_Activity_DefaultSubElement
{
    protected $data;
    protected $count = 0;
    
    public function init()
    {
        parent::init();
        
        $this->getElement('amount')->setLabel('Incoming Fund Amount');
        $this->getElement('start_date')->setLabel('Incoming Fund Date');
        $this->getElement('currency')->setLabel('Incoming Fund Currency');
        $this->removeElement('end_date');
        
        $this->setElementsBelongTo("incommingFund[{$this->count}]");
    }
    
    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function setCount($count)
    {
        $this->count = $count;
    }
}