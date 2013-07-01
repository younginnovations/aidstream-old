<?php
class Simplified_Form_Activity_Transaction_Expenditure extends Simplified_Form_Activity_Transaction_DefaultTransactionSubElement
{
    protected $data;
    protected $count = 0;
    
    public function init()
    {
        parent::init();
        
        $this->removeElement('end_date');
        
        foreach($this->getElements() as $item_name=>$element)
        {
            $element->addDecorators( array(
                        array('HtmlTag',
                              array(
                                    'tag'        =>'<div>',
                                    'placement'  =>'PREPEND',
                                    'class'      =>'help simplified-expenditure-'.$item_name
                                )
                            ),
                        array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'clearfix form-item'))
                    )
            );
        }
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