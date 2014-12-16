<?php

class Iati_Aidstream_Form_Activity_RecipientCountry extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']); 
        
        $countryCode = $model->getCodeArray('Country', null, '1' , true);
        $form['code'] = new Zend_Form_Element_Select('code');
        $form['code']->setLabel('Country Code')  
            ->setValue($this->data['@code'])
            ->setRequired()    
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($countryCode);

        $form['percentage'] = new Zend_Form_Element_Text('percentage');
        $form['percentage']->setLabel('Percentage')  
            ->setValue($this->data['@percentage'])
            ->addValidator(new App_Validate_NumericValue())
            ->setAttrib('class' , 'form-text');
        
        $this->addElements($form);
        return $this;
    }
}