<?php
/**
 *
 * Default transaction subform containing amount,start date and end date to be used by simplified form elements.
 * @author bhabishyat
 *
 */
class Simplified_Form_Activity_Transaction_DefaultTransactionSubElement extends Iati_SimplifiedForm
{
    public function init(){
        $this->setAttrib('class' , 'simplified-sub-element')
            ->setIsArray(true);
            
        $model = new Model_Wep();
        $form = array();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $form['start_id'] = new Zend_Form_Element_Hidden('start_id');
        $form['start_id']->setValue($this->data['start_id']);
        
        $form['end_id'] = new Zend_Form_Element_Hidden('end_id');
        $form['end_id']->setValue($this->data['end_id']);
        
        $form['value_id'] = new Zend_Form_Element_Hidden('value_id');
        $form['value_id']->setValue($this->data['value_id']);
        $this->addElements($form);

        $form['amount'] = new Zend_Form_Element_Text('amount');
        $form['amount']->setLabel('Amount')
            ->setRequired()
            ->addFilter(new Iati_Filter_Currency())
            ->setValue($this->data['amount'])
            ->addValidator(new App_Validate_Numeric())
            ->setAttrib('class', 'form-text');
        
        $currency = $model->getCodeArray('Currency' , '' , 1 , true);
        $form['currency'] = new Zend_Form_Element_Select('currency');
        $form['currency']->setLabel('Currency')
            ->addMultiOptions($currency)
            ->setValue($this->data['currency'])
            ->setAttrib('class', 'form-select');

        $form['start_date'] = new Zend_Form_Element_Text('start_date');
        $form['start_date']->setLabel('Value Date')
            ->setRequired()
            ->setValue($this->data['start_date'])
            ->setAttrib('class', 'form-text datepicker');

        $form['end_date'] = new Zend_Form_Element_Text('end_date');
        $form['end_date']->setLabel('End Date')
            ->setValue($this->data['end_date'])
            ->setAttrib('class', 'form-text datepicker');
            

        foreach($form as $item_name=>$element)
        {
            $form[$item_name]->addDecorators( array(
                        array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'clearfix form-item'))
                    )
            );
        }
        
        $this->addElements($form);
    }
}