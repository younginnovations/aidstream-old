<?php
class Simplified_Form_Activity_Default extends App_Form
{
    protected $data;
    public function init(){

        $model = new Model_Wep();
        
        $this->setAttrib('id' , 'simplified-default-form')
            ->setIsArray(true);
        $form = array();

        $form['identifier'] = new Zend_Form_Element_Text('identifier');
        $form['identifier']->setLabel('Project Identifier')
            ->setRequired()
            ->setAttrib('class', 'form-text');
            
        $form['title'] = new Zend_Form_Element_Text('title');
        $form['title']->setLabel('Title')
            ->setRequired()
            ->setAttrib('class', 'form-text');
            
        $form['description'] = new Zend_Form_Element_Textarea('description');
        $form['description']->setLabel('Description')
            ->setRequired()
            ->setAttrib('COLS', '40')
            ->setAttrib('ROWS', '4')
            ->setAttrib('class', 'form-text');
            
        $form['funding_org'] = new Zend_Form_Element_Text('funding_org');
        $form['funding_org']->setLabel('Funding Organisation')
            ->setRequired()
            ->setAttrib('class', 'form-text');
            
        $form['start_date'] = new Zend_Form_Element_Text('start_date');
        $form['start_date']->setLabel('Actual Start Date')
            ->setRequired()
            ->setAttrib('class', 'form-text datepicker');
        
        $form['end_date'] = new Zend_Form_Element_Text('end_date');
        $form['end_date']->setLabel('Actual End Date')
            ->setRequired()
            ->setAttrib('class', 'form-text datepicker');
        
        $form['document_url'] = new Zend_Form_Element_Text('document_url');
        $form['document_url']->setLabel('Document Url')
            ->setAttrib('class', 'form-text');
            
        $categoryCodes = $model->getCodeArray('DocumentCategory' , '' , 1 , true);
        $form['document_category_code'] = new Zend_Form_Element_Select('document_category_code');
        $form['document_category_code']->setLabel('Document Category Code')
            ->addMultiOptions($categoryCodes)
            ->setAttrib('class', 'form-text');
            
        $form['document_title'] = new Zend_Form_Element_Text('document_title');
        $form['document_title']->setLabel('Document Title')
            ->setAttrib('class', 'form-text');
            
        $form['location_name'] = new Zend_Form_Element_Text('location_name');
        $form['location_name']->setLabel('District/VDC Name')
            ->setRequired()
            ->setAttrib('class', 'form-text');
            
        
        
        $this->addElements($form);
        
        // Budget
        $budget = new Simplified_Form_Activity_Budget();
        $this->addSubForm($budget , 'budget');
        $budget->removeDecorator('form');
        
        // Commitment
        $commitment = new Simplified_Form_Activity_Transaction_Commitment();
        $this->addSubForm($commitment , 'commitment');
        $commitment->removeDecorator('form');
        
        // Disbursement
        $incommingFund = new Simplified_Form_Activity_Transaction_IncommingFund();
        $this->addSubForm($incommingFund , 'incommingFund');
        $incommingFund->removeDecorator('form');
        
        // Expenditure
        $expenditure = new Simplified_Form_Activity_Transaction_Expenditure();
        $this->addSubForm($expenditure , 'expenditure');
        $expenditure->removeDecorator('form');
        
        $form['sector'] = new Zend_Form_Element_Text('sector');
        $form['sector']->setLabel('Sector')
            ->setRequired()
            ->setAttrib('class', 'form-text');
        $this->addElements($form);
        
        foreach($form as $item_name=>$element)
        {
            $form[$item_name]->addDecorators( array(
                        array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'clearfix form-item'))
                    )
            );
        }
        
        $submit = new Zend_Form_Element_Submit('Submit');
        $submit->setValue('Save');
        $this->addElement($submit);
    }
    
    public function setData($data)
    {
        $this->data = $data;
    }
}
