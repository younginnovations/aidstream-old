<?php

class Form_Admin_Editpermission extends App_Form
{
    public function edit($defaultFields = '')
    {
        //print_r($defaultFields);exit;
        $button = new Zend_Form_Element_Button('button');
        $button->setLabel('Check All');
        $button->setAttrib('class', 'check-uncheck');
        
        $this->addElement($button);
        
        foreach($defaultFields['fields'] as $key=>$eachDefault){
            $default_fields[$key] =  ucwords(str_replace("_", " ", $key));
            if($eachDefault == '1'){
                $checked[] = $key;
            }
        }
        $this->addElement('multiCheckbox', 'default_fields', array(
                        'disableLoadDefaultDecorators' => true,
                        'separator'    => '&nbsp;',
                        'multiOptions' => $default_fields,
                        'value' => $checked,
                        'decorators'   => array(
                                    'ViewHelper',
                                    'Errors',
                                array('HtmlTag', array('tag' => 'p'))          
                        )
        ));
        
        $signup = new Zend_Form_Element_Submit('submit');
        $signup->setValue('Submit')->setAttrib('id', 'Submit');
        //$this->addElement('default_fields');
        $this->addDisplayGroup(array('button', 'default_fields',), 'field3',array('legend'=>'User Permissions'));
       
        $this->addElement($signup);
        $this->setMethod('post');
    }

}