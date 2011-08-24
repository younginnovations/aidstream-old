<?php
class Form_Wep_EditIatiActivity extends App_Form
{
    public function edit( $account_id = '')
    {
        $form = array();

        $model = new Model_Wep();
        $language = $model->getCodeArray('Language',null,'1');
        $currency = $model->getCodeArray('Currency', null, '1');


        $form['xml_lang'] = new Zend_Form_Element_Select('xml_lang');
        $form['xml_lang']->setLabel('Language')
        ->setAttrib('class', 'form-select')
        ->addMultiOption('', 'Select anyone')->setRequired();
       
        
        foreach($language as $key => $eachLanguage){
            $form['xml_lang']->addMultiOption($key, $eachLanguage);
        }
         
        $form['default_currency'] = new Zend_Form_Element_Select('default_currency');
        $form['default_currency']->setAttrib('class', 'form-select')
                                ->setLabel('Default Currency')
                                ->setRequired()->addMultiOption('', 'Select anyone');
      
        
        foreach($currency as $key => $eachCurrency){
            $form['default_currency']->addMultiOption($key, $eachCurrency);
        }

        $form['hierarchy'] = new Zend_Form_Element_Text('hierarchy');
        $form['hierarchy']->setAttrib('class', 'form-text')->setLabel('Hierarchy');

        $this->addElements($form);
        $this->addDisplayGroup(array('xml_lang', 'default_currency', 'hierarchy'), 
                                    'field1',array('legend'=>'Activity'));
        
        $save = new Zend_Form_Element_Submit('save');
        $save->setValue('Save')->setAttrib('class','form-submit');
        $this->addElement($save);
        $this->setMethod('post');
        
    }
}