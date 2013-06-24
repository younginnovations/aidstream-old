<?php
class Simplified_Form_Activity_Result extends Iati_SimplifiedForm
{
    protected $data;
    protected $count = 0;
    
    public function init()
    {
        parent::init();
        $this->setAttrib('class' , 'simplified-sub-element')
            ->setIsArray(true);
            
        $model = new Model_Wep();
        $form = array();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
    
        
        $form['title_id'] = new Zend_Form_Element_Hidden('title_id');
        $form['title_id']->setValue($this->data['title_id']);
        
        $form['description_id'] = new Zend_Form_Element_Hidden('description_id');
        $form['description_id']->setValue($this->data['description_id']);
        
        $form['indicator_id'] = new Zend_Form_Element_Hidden('indicator_id');
        $form['indicator_id']->setValue($this->data['indicator_id']);
        
        $form['indicator_title_id'] = new Zend_Form_Element_Hidden('indicator_title_id');
        $form['indicator_title_id']->setValue($this->data['indicator_title_id']);
        
        $form['period_id'] = new Zend_Form_Element_Hidden('period_id');
        $form['period_id']->setValue($this->data['period_id']);
        
        $form['actual_id'] = new Zend_Form_Element_Hidden('actual_id');
        $form['actual_id']->setValue($this->data['actual_id']);
        
        $form['period_end_id'] = new Zend_Form_Element_Hidden('period_end_id');
        $form['period_end_id']->setValue($this->data['period_end_id']);
        
        
        $resultTypeCodes = $model->getCodeArray('ResultType' , '' , 1 , true);
        $form['result_type'] = new Zend_Form_Element_Select('result_type');
        $form['result_type']->setLabel('Result Type')
            ->setRequired()
            ->addMultiOptions($resultTypeCodes)
            ->setValue($this->data['result_type'])
            ->setAttrib('class', 'form-select');

        $form['title'] = new Zend_Form_Element_Text('title');
        $form['title']->setLabel('Title')
            ->setRequired()
            ->setValue($this->data['title'])
            ->setAttrib('class', 'form-text');
            
        $form['description'] = new Zend_Form_Element_Textarea('description');
        $form['description']->setLabel('Description')
            ->setRequired()
            ->setValue($this->data['description'])
            ->setAttrib('COLS', '40')
            ->setAttrib('ROWS', '4')
            ->setAttrib('class', 'form-text');
            
        $form['indicator'] = new Zend_Form_Element_Text('indicator');
        $form['indicator']->setLabel('Indicator')
            ->setRequired()
            ->setValue($this->data['indicator'])
            ->setAttrib('class', 'form-text');
            
        $form['achievement'] = new Zend_Form_Element_Text('achievement');
        $form['achievement']->setLabel('Achievement')
            ->setRequired()
            ->setValue($this->data['achievement'])
            ->setAttrib('class', 'form-text');
            
        $form['end_date'] = new Zend_Form_Element_Text('end_date');
        $form['end_date']->setLabel('As of')
            ->setRequired()
            ->setValue($this->data['end_date'])
            ->setAttrib('class', 'form-text datepicker simplified-result-date');
                
        $this->addElements($form);

        $this->setElementsBelongTo("result[{$this->count}]");
        // Add remove button
        $remove = new Iati_Form_Element_Note('remove');
        $remove->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'simplified-remove-element'));
        $remove->setValue("<a href='#' class='button' value='Result'> Remove element</a>");
        $this->addElement($remove);
        
        foreach($form as $item_name=>$element)
        {
            $form[$item_name]->addDecorators( array(
                        array('HtmlTag',
                              array(
                                    'tag'        =>'<div>',
                                    'placement'  =>'PREPEND',
                                    'class'      =>'help siplified-result-'.$item_name
                                )
                            ),
                        array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'clearfix form-item'))
                    )
            );
        }
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