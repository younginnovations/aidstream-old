<?php
class Form_Wep_ReportingOrganisation extends App_Form
{

    public function add($state = 'add', $account_id = ''){
        $form = array();
        $model = new Model_Wep();

        $organisationType = $model->getCodeArray('OrganisationType', null, '1');
        $language = $model->getCodeArray('Language',null,'1');
        $rowSet = $model->getRowsByFields('default_field_values', 'account_id', $account_id);
        $defaultValues = unserialize($rowSet[0]['object']);
        $default = $defaultValues->getDefaultFields();

        $form['reporting_org_xmllang'] = new Zend_Form_Element_Select('reporting_org_xmllang');
        $form['reporting_org_xmllang']->setLabel('Language')
                                        ->addMultiOption('', 'Select anyone')->setValue($default['language'])
                                        ->setAttrib('class', 'form-select')
                                        ->setDecorators( array(
                                                            'ViewHelper',
                                                            'Errors',
                                                            'Label',
                                                            array('HtmlTag', array(
                                                                                   'tag'        =>'<div>',
                                                                                   'placement'  =>'APPEND',
                                                                                   'class'      =>'help reporting_org-xml_lang'
                                                                                   )
                                                                ),
                                                            array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'clearfix form-item'))
                                                        )
                                                );;
        foreach($language as $key => $eachLanguage){
            $form['reporting_org_xmllang']->addMultiOption($key, $eachLanguage);
        }

         
        $form['reporting_org_ref'] = new Zend_Form_Element_Text('reporting_org_ref');
        $form['reporting_org_ref']->setLabel('Reporting Organisation Identifier')
                                    ->setValue($default['reporting_org_ref'])
                                    ->setRequired()->setAttrib('class', 'form-select')
                                    ->setDecorators( array(
                                                            'ViewHelper',
                                                            'Errors',
                                                            'Label',
                                                            array('HtmlTag', array(
                                                                                   'tag'        =>'<div>',
                                                                                   'placement'  =>'APPEND',
                                                                                   'class'      =>'help reporting_org-ref'
                                                                                   )
                                                                ),
                                                            array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'clearfix form-item'))
                                                        )
                                                );

        $form['reporting_org_type'] = new Zend_Form_Element_Select('reporting_org_type');
        $form['reporting_org_type']->setLabel('Organisation Type')
                                    ->setValue($default['reporting_org_type'])
                                    ->addMultiOption('', 'Select anyone')->setAttrib('class', 'form-select')
                                    ->setDecorators( array(
                                                            'ViewHelper',
                                                            'Errors',
                                                            'Label',
                                                            array('HtmlTag', array(
                                                                                   'tag'        =>'<div>',
                                                                                   'placement'  =>'APPEND',
                                                                                   'class'      =>'help reporting_org-type'
                                                                                   )
                                                                ),
                                                            array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'clearfix form-item'))
                                                        )
                                        );
        foreach($organisationType as $key => $eachType){
            $form['reporting_org_type']->addMultiOption($key, $eachType);
        }

        $form['reporting_org_text'] = new Zend_Form_Element_Text('reporting_org_text');
        $form['reporting_org_text']->setLabel('Name')->setValue($default['reporting_org'])
                                    ->setRequired()->setAttrib('class', 'form-text')
                                    ->setDecorators( array(
                                                            'ViewHelper',
                                                            'Errors',
                                                            'Label',
                                                            array('HtmlTag', array(
                                                                                   'tag'        =>'<div>',
                                                                                   'placement'  =>'APPEND',
                                                                                   'class'      =>'help reporting_org-text'
                                                                                   )
                                                                ),
                                                            array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'clearfix form-item'))
                                                        )
                                                );
        
        
        $this->addElements($form);
        $this->addDisplayGroup(array('reporting_org_xmllang', 'reporting_org_ref', 
                                    'reporting_org_type', 'reporting_org_text'),
                                     'field',array('legend'=>'Reporting Organisation'));
        
        $group = $this->getDisplayGroup('field');
        $group->addDecorators(array(
            array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'default-activity-list'))
        ));

//        $this->setMethod('post');
        
    }
}