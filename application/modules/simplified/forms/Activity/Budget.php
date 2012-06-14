<?php
class Simplified_Form_Activity_Budget extends Simplified_Form_Activity_DefaultSubElement
{
    protected $data;
    protected $count;
    
    public function init()
    {
        parent::init();
        $this->getElement('amount')->setLabel('Budget Amount');
        $this->getElement('start_date')->setLabel('Budget Start Date');
        $this->getElement('end_date')->setLabel('Budget End Date');
        $this->getElement('currency')->setLabel('Budget Currency');
        
        $signedDate = new Zend_Form_Element_Text('signed_date');
        $signedDate->setLabel('Contract Signed  Date')
            ->setValue($this->data['signed_date'])
            ->setAttrib('class', 'form-text datepicker');
        $signedDate->addDecorators( array(
                       array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'clearfix form-item'))
                   )
        );
        
        $this->addElement($signedDate);
        
        $this->setElementsBelongTo("budget[{$this->count}]");
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