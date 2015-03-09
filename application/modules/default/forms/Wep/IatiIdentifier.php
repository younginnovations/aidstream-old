<?php
class Form_Wep_IatiIdentifier extends App_Form
{   

    public function add($state = 'add', $account_id = '')
    {
        
        $form = array();
                
        $form['activity_identifier'] = new Zend_Form_Element_Text('activity_identifier');
        $form['activity_identifier']->setLabel('Activity Identifier')
                                    ->setRequired()
                                    ->addValidator('Db_NoRecordExists', false, 
                                        array('table' => 'iati_identifier', 'field' => 'activity_identifier',
                                            'messages' => array(
                                                Zend_Validate_Db_NoRecordExists::ERROR_RECORD_FOUND => 'Activity Identifier already in use.'
                                            )))
                                    ->setAttrib('class', 'form-text')
                                    ->setDecorators(
                                        array(
                                            'ViewHelper',
                                            'Errors',
                                            'Label',
                                            array(
                                                'HtmlTag',
                                                array(
                                                    'tag'        =>'<div>',
                                                    'placement'  =>'PREPEND',
                                                    'class'      =>'help activity_identifier'
                                                )
                                            ),
                                            array(
                                                array( 'wrapperAll' => 'HtmlTag' ),
                                                array(
                                                    'tag' => 'div',
                                                    'class'=>'clearfix form-item'
                                                )
                                            )
                                        )
                                    );
        
        $form['iati_identifier_text'] = new Zend_Form_Element_Text('iati_identifier_text');
        $form['iati_identifier_text']->setLabel('IATI Identifier')
                                    ->setRequired()
                                    ->setAttrib('class', 'form-text')
                                    ->setAttrib('readonly', true)
                                    ->setDecorators(
                                        array(
                                            'ViewHelper',
                                            'Errors',
                                            'Label',
                                            array(
                                                'HtmlTag',
                                                array(
                                                    'tag'        =>'<div>',
                                                    'placement'  =>'PREPEND',
                                                    'class'      =>'help identifier-text'
                                                )
                                            ),
                                            array(
                                                array( 'wrapperAll' => 'HtmlTag' ),
                                                array( 'tag' => 'div','class'=>'clearfix form-item')
                                            )
                                        )
                                    );
        
        $form['reporting_org'] = new Zend_Form_Element_Hidden('reporting_org');
                       
        $this->addElements($form);
        $this->addDisplayGroup(
            array('reporting_org' , 'activity_identifier' , 'iati_identifier_text'),
            'field',
            array('legend'=>'IATI Identifier')
        );
        
        $group = $this->getDisplayGroup('field');
        $group->addDecorators(array(
            array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'default-activity-list'))
        ));
        
        $save = new Zend_Form_Element_Submit('Save');
        $save->setValue('Save')->setAttrib('class','form-submit');
        $this->addElement($save);
        $this->setMethod('post');
    }
}