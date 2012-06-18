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
        // Add remove button
        $remove = new Iati_Form_Element_Note('remove');
        $remove->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'simplified-remove-element'));
        $remove->setValue("<a href='#' class='button' value='Transaction'> Remove element</a>");
        $this->addElement($remove);
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