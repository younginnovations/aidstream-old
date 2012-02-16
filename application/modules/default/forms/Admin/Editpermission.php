<?php

class Form_Admin_Editpermission extends App_Form
{
    public function edit($defaultFields = '')
    {
        //print_r($defaultFields);exit;
        $button = new Zend_Form_Element_Button('button');
        $button->setLabel('Check All')
            ->addDecorators(array(array(
                                        array('wrapperAll' => 'HtmlTag') ,
                                        array('tag' => 'div' , 'class' => 'clearfix form-item')
                                        )
                                  ))
            ->setAttrib('class', 'check-uncheck');
        
        $this->addElement($button);
        //print_r($defaultFields);exit;
        foreach($defaultFields['fields'] as $key=>$eachDefault){
            if($key == 'add_activity' || $key == 'add_activity_elements'){
                $key = 'add';
                $default_fields['add'] = 'Add';
            }
            elseif($key == 'edit_activity' || $key == 'edit_activity_elements'){
                $key = 'edit';
                $default_fields['edit'] = 'Edit';
            }
            else if($key == 'delete_activity' || $key == 'delete_activity_elements'){
                $key = 'delete';
                $default_fields['delete'] = 'Delete';
            }
            else if($key == 'view_activities'){
                continue;
            }
            else{
                $default_fields[$key] =  ucwords(str_replace("_", " ", $key));
            }
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
        $group = $this->getDisplayGroup('field3');
        $group->setDecorators(array(
            'FormElements',
            'Fieldset',
            array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'default-activity-list'))
        ));
        $this->addElement($signup);
        $this->setMethod('post');
    }

}