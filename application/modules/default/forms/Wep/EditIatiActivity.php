<?php
class Form_Wep_EditIatiActivity extends App_Form
{
    public function edit( $account_id = '')
    {
        $form = array();
//        print $status;exit;

        $model = new Model_Viewcode();
        $language = $model->getCode('Language',null,'1');
        $currency = $model->getCode('Currency', null, '1');

        $form['xml_lang'] = new Zend_Form_Element_Select('xml_lang');
        $form['xml_lang']->setLabel('Language')
        ->setAttrib('class', 'form-select')->addMultiOption('', 'Select anyone')->setRequired();
       
        
        foreach($language[0] as $eachLanguage){
            $form['xml_lang']->addMultiOption($eachLanguage['id'], $eachLanguage['Code']);
        }
         
        $form['default_currency'] = new Zend_Form_Element_Select('default_currency');
        $form['default_currency']->setAttrib('class', 'form-select')->setLabel('Default Currency')
        ->setRequired()->addMultiOption('', 'Select anyone');
      
        
        foreach($currency[0] as $eachCurrency){
            $form['default_currency']->addMultiOption($eachCurrency['id'], $eachCurrency['Code']);
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