<?php
/**
 *
 * Default subform containing amount,start date and end date to be used by simplified form elements.
 * @author bhabishyat
 *
 */
class Simplified_Form_Activity_DefaultSubElement extends App_Form
{
    public function init(){
        $this->setAttrib('class' , 'simplified-sub-element')
            ->setIsArray(true);
            
        $model = new Model_Wep();
        $form = array();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);

        $form['amount'] = new Zend_Form_Element_Text('amount');
        $form['amount']->setLabel('Amount')
            ->setValue($this->data['amount'])
            ->setAttrib('class', 'form-text');

        $form['start_date'] = new Zend_Form_Element_Text('start_date');
        $form['start_date']->setLabel('Start Date')
            ->setValue($this->data['start_date'])
            ->setAttrib('class', 'form-text datepicker');

        $form['end_date'] = new Zend_Form_Element_Text('end_date');
        $form['end_date']->setLabel('End Date')
            ->setValue($this->data['end_date'])
            ->setAttrib('class', 'form-text datepicker');
            
        $currency = $model->getCodeArray('Currency' , '' , 1 , true);
        $form['currency'] = new Zend_Form_Element_Select('currency');
        $form['currency']->setLabel('Currency')
            ->addMultiOptions($currency)
            ->setValue($this->data['currency'])
            ->setAttrib('class', 'form-text');

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