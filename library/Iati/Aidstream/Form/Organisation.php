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
        
        $form['account_id'] = new Zend_Form_Element_Hidden('account_id');
        $form['account_id']->setValue($identity->account_id);
        
        $form['last_updated_datetime'] = new Zend_Form_Element_Hidden('last_updated_datetime');
        $form['last_updated_datetime']->setValue(($this->data['@last_updated_datetime'])?$this->data['@last_updated_datetime']:date('Y-m-d h:i:s'));
                
        $lang = $model->getCodeArray('Language', null, '1' , true);
        $form['xml_lang'] = new Zend_Form_Element_Select('xml_lang');
        $form['xml_lang']->setLabel('Language')
            ->setValue($this->data['@xml_lang'])
            ->setAttrib('class' , 'form-select')
            ->addMultioptions($lang);
            
        $form['default_currency'] = new Zend_Form_Element_Text('default_currency');
        $form['default_currency']->setLabel('Default Currency')
            ->setValue($this->data['@default_currency'])
            ->setAttribs(array('class' => 'form-text'));

        $this->addElements($form);
        return $this;
    }
}