<?php
class Simplified_Form_Activity_Transaction_Expenditure extends Simplified_Form_Activity_DefaultSubElement
{
    protected $data;
    protected $count = 0;
    
    public function init()
    {
        parent::init();
        
        $this->getElement('amount')->setLabel('Expenditure Amount');
        $this->getElement('start_date')->setLabel('Expenditure Date');
        $this->getElement('currency')->setLabel('Expenditure Currency');
        $this->removeElement('end_date');
        
        $this->setElementsBelongTo("expenditure[{$this->count}]");
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