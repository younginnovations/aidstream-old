<?php

class Iati_Aidstream_Form_Organisation extends Iati_Core_BaseForm
{  
    public function getFormDefination()
    {
        $model = new Model_Wep();
        $identity = Zend_Auth::getInstance()->getIdentity();
        
        $this->setAttrib('class' , 'simplified-sub-element');
        
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
        $form['default_currency']->setLabel('Currency')
            ->setValue($this->data['@default_currency'])
            ->setRequired()    
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($currency);

        $this->addElements($form);
        return $this;
    }
    
    public function addSubmitButton($label , $saveAndViewlabel = 'Save and View')
    {
        $this->addElement('submit' , 'update' , array(
            'label' => 'update' ,
            'required' => false ,
            'ignore' => false ,
                )
        );

    }
}