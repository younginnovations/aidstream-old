<?php
class Simplified_Form_Activity_Transaction_Commitment extends Simplified_Form_Activity_DefaultSubElement
{
    protected $data;
    protected $count = 0;
    
    public function init()
    {
        parent::init();
        
        $this->getElement('amount')->setLabel('Commited Amount');
        $this->getElement('start_date')->setLabel('Commited Date');
        $this->getElement('currency')->setLabel('Commited Currency');
        $this->removeElement('end_date');
        
        $this->setElementsBelongTo("commitment[{$this->count}]");
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