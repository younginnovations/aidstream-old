<?php

class Iati_Aidstream_Form_Activity extends Iati_Core_BaseForm
{  
    public function getFormDefination()
    {
        $model = new Model_Wep();
        $form = array();
        
        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $lang = $model->getCodeArray('Language', null, '1' , true);
        $form['xml_lang'] = new Zend_Form_Element_Select('xml_lang');
        $form['xml_lang']->setLabel('Language')
            ->setValue($this->data['@xml_lang'])
            ->setAttrib('class' , 'form-select')
            ->setRequired()    
            ->addMultioptions($lang);
        
        $currency = $model->getCodeArray('Currency', null, '1' , true);
        $form['default_currency'] = new Zend_Form_Element_Select('default_currency');
        $form['default_currency']->setLabel('Default Currency')
            ->setValue($this->data['@default_currency'])
            ->setRequired()    
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($currency);
        
        $form['hierarchy'] = new Zend_Form_Element_Text('hierarchy');
        $form['hierarchy']->setLabel('Hierarchy')    
            ->setValue($this->data['@hierarchy'])
            ->setAttrib('class' , 'form-text'); 

        $this->addElements($form);
        return $this;
    }
    
    public function addSubmitButton($label , $saveAndViewlabel = 'Save and View')
    {
        $this->addElement('submit' , 'update' , array(
            'label' => 'save' ,
            'required' => false ,
            'ignore' => false ,
                )
        );

    }
}