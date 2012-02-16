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
        
        //This code is used to append a <div> with help class for all form elements, used for displaying help
        foreach($form as $item_name=>$element)
        {
            $form[$item_name]->setDecorators( array(
                        'ViewHelper',
                        'Errors',
                        'Label',
                        array('HtmlTag', array(
                                               'tag'        =>'<div>',
                                               'placement'  =>'APPEND',
                                               'class'      =>'help activity-'.$item_name
                                               )
                            ),
                        array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'clearfix form-item'))
                    )
            );
        }
        
        $this->addElements($form);
        $this->addDisplayGroup(array('xml_lang', 'default_currency', 'hierarchy'), 
                                    'field1',array('legend'=>'Activity'));
        
        $group = $this->getDisplayGroup('field1');
        $group->addDecorators(array(
            array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'default-activity-list'))
        ));
        $save = new Zend_Form_Element_Submit('save');
        $save->setValue('Save')->setAttrib('class','form-submit');
        $this->addElement($save);
        $this->setMethod('post');
        $this->setAttrib('id','activity-edit-form');
        
    }
}