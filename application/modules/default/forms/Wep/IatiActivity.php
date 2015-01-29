<?php
class Form_Wep_IatiActivity extends App_Form
{
    public function add($status = "add", $account_id = '')
    {
        $form = array();

        $model = new Model_Wep();
        $language = $model->getCodeArray('Language',null,'1');
        $currency = $model->getCodeArray('Currency', null, '1');

        if($status != 'edit'){
        $rowSet = $model->getRowsByFields('default_field_values', 'account_id', $account_id);
        $defaultValues = unserialize($rowSet[0]['object']);
        $default = $defaultValues->getDefaultFields();

        }
        $form['xml_lang'] = new Zend_Form_Element_Select('xml_lang');
        $form['xml_lang']->setLabel('Language')
            ->setAttrib('class', 'form-select')
            ->setRequired()
            ->addMultiOption('', 'Select one of the following option:');
        if($status != 'edit'){
            $form['xml_lang']->setValue($default['language']);
        }
        
        foreach($language as $key => $eachLanguage){
            $form['xml_lang']->addMultiOption($key, $eachLanguage);
        }
         
        $form['default_currency'] = new Zend_Form_Element_Select('default_currency');
        $form['default_currency']->setLabel('Default Currency')
            ->setRequired()
            ->setAttrib('class', 'form-select')
            ->addMultiOption('', 'Select one of the following option:');
         if($status != 'edit'){
            $form['default_currency']->setValue($default['currency']);
        }
        
        foreach($currency as $key => $eachCurrency){
            $form['default_currency']->addMultiOption($key, $eachCurrency);
        }

        $form['hierarchy'] = new Zend_Form_Element_Text('hierarchy');
        $form['hierarchy']->setAttrib('class', 'form-text')->setLabel('Hierarchy');
        
        $form['linked_data_uri'] = new Zend_Form_Element_text('linked_data_uri');
        $form['linked_data_uri']->setLabel('Linked Data Uri')
            ->setAttrib('class', 'form-text');
         if($status != 'edit'){
            $form['linked_data_uri']->setValue($default['linked_data_default']);
        }

        //This code is used to append a <div> with help class for all form elements, used for displaying help
        foreach($form as $item_name=>$element)
        {
            $form[$item_name]->setDecorators( array(
                        'ViewHelper',
                        'Errors',
                        'Label',
                        array(
                            'HtmlTag',
                            array(
                                'tag'        =>'<div>',
                                'placement'  =>'PREPEND',
                                'class'      =>'help activity-'.$item_name
                            )
                        ),
                        array(
                            array( 'wrapperAll' => 'HtmlTag' ),
                            array( 'tag' => 'div','class'=>'clearfix form-item')
                        )
                    )
            );
        }
        $this->addElements($form);

        $this->addDisplayGroup(
            array('xml_lang', 'default_currency', 'hierarchy' , 'linked_data_uri'), 
            'field1',
            array('legend'=>'Activity')
        );
        $activity = $this->getDisplayGroup('field1');
        $activity->addDecorators( array(
                array(
                    array('wrapperAll' => 'HtmlTag'),
                    array('tag' => 'div', 'class' => 'default-activity-list')
                )
            )
        );
        
        /*
        $form1 = new Form_Wep_ReportingOrganisation();
        $form1->add('add', $account_id);
        $this->addSubForm($form1, 'Reporting Organisation');
        
        $iati_identifier = new Zend_Form_Element_Text('iati_identifier_text');
        $iati_identifier->setLabel('Iati Identifier')->setAttrib('class', 'form-text')
                                ->setRequired()
                                ->setDecorators( array(
                                                            'ViewHelper',
                                                            'Errors',
                                                            'Label',
                                                            array('HtmlTag', array(
                                                                                   'tag'        =>'<div>',
                                                                                   'placement'  =>'APPEND',
                                                                                   'class'      =>'help identifier-text'
                                                                                   )
                                                                ),
                                                            array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'clearfix form-element'))
                                                        )
                                                );
        
        $this->addElement($iati_identifier);
        
        
        $this->addDisplayGroup(array('iati_identifier_text'), 'field',array('legend'=>'Iati Identifier'));
        $identifier = $this->getDisplayGroup('field');
        $identifier->addDecorators( array(
                array(array('wrapperAll' => 'HtmlTag'), array('tag' => 'div', 'class' => 'default-activity-list'))
            )
        );
        */
        $form1 = new Form_Wep_IatiIdentifier();
        $form1->add('add', $account_id);
        $form1->removeElement('save');
        $this->addSubForm($form1, 'IATI Identifier');
        
        $save = new Zend_Form_Element_Submit('save');
        $save->setValue('Save')->setAttrib('class','form-submit');
        $this->addElement($save);
        $this->setMethod('post');
    }
}
