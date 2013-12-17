<?
class Form_Wep_DefaultFieldValues extends App_Form
{
    
    public function load($defaults){
        $form = array();
        $model = new Model_Wep();
        
        $currency = $model->getCodeArray('Currency',null,'1');
        $form['default_currency'] = new Zend_Form_Element_Select('default_currency');
        $form['default_currency']->setRequired()
            ->setLabel('Default Currency')
            ->addMultiOption('', 'Select anyone')
            ->setValue($defaults['field_values']['currency'])
            ->setAttrib('class', 'form-select');
        foreach($currency as $key => $eachCurrency){
            $form['default_currency']->addMultiOption($key, $eachCurrency);
        }
        
        $language = $model->getCodeArray('Language',null,'1');
        $form['default_language'] = new Zend_Form_Element_Select('default_language');
        $form['default_language']->setRequired()
            ->setLabel('Default Language')
            ->addMultiOption('', 'Select anyone')
            ->setValue($defaults['field_values']['language'])
            ->setAttrib('class', 'form-select');
        foreach($language as $key => $eachLanguage){
            $form['default_language']->addMultiOption($key, $eachLanguage);
        }
        
        $form['hierarchy'] = new Zend_Form_Element_Text('hierarchy');
        $form['hierarchy']->setLabel('Default Hierarchy')
            ->setAttrib('class' , 'form-text')
            ->setValue($defaults['field_values']['hierarchy']);
            
        $form['linked_data_default'] = new Zend_Form_Element_Text('linked_data_default');
        $form['linked_data_default']->setLabel('Linked Data Default')
            ->setAttrib('class' , 'form-text')
            ->setValue($defaults['field_values']['linked_data_default']);
                                    
        $form['default_collaboration_type'] = new Zend_Form_Element_Select('default_collaboration_type');
        $form['default_collaboration_type']->setLabel('Default Collaboration Type')
            ->setValue($defaults['field_values']['collaboration_type'])
            ->addMultiOption('','Select Anyone')
            ->setAttrib('class', 'form-select');
        $collaborationTypes = $model->getCodeArray('CollaborationType',null,'1');
        foreach($collaborationTypes as $key => $collaborationType){
            $form['default_collaboration_type']->addMultiOption($key, $collaborationType);
        }
        
        $form['default_flow_type'] = new Zend_Form_Element_Select('default_flow_type');
        $form['default_flow_type']->setLabel('Default Flow Type')
            ->setValue($defaults['field_values']['flow_type'])
            ->addMultiOption('','Select Anyone')
            ->setAttrib('class', 'form-select');
        $flowTypes = $model->getCodeArray('FlowType',null,'1');
        foreach($flowTypes as $key => $flowType){
            $form['default_flow_type']->addMultiOption($key, $flowType);
        }
        
        $form['default_finance_type'] = new Zend_Form_Element_Select('default_finance_type');
        $form['default_finance_type']->setLabel('Default Finance Type')
            ->setValue($defaults['field_values']['finance_type'])
            ->addMultiOption('','Select Anyone')
            ->setAttrib('class', 'form-select');
        $financeTypes = $model->getCodeArray('FinanceType',null,'1');
        foreach($financeTypes as $key => $financeType){
            $form['default_finance_type']->addMultiOption($key, $financeType);
        }
        
        $form['default_aid_type'] = new Zend_Form_Element_Select('default_aid_type');
        $form['default_aid_type']->setLabel('Default Aid Type')
            ->setValue($defaults['field_values']['aid_type'])
            ->addMultiOption('','Select Anyone')
            ->setAttrib('class', 'form-select');
        $aidTypes = $model->getCodeArray('AidType',null,'1');
        foreach($aidTypes as $key => $aidType){
            $form['default_aid_type']->addMultiOption($key, $aidType);
        }
        
        $form['default_tied_status'] = new Zend_Form_Element_Select('default_tied_status');
        $form['default_tied_status']->setLabel('Default Tied Status')
            ->setValue($defaults['field_values']['tied_status'])
            ->addMultiOption('','Select Anyone')
            ->setAttrib('class', 'form-select');
        $tiedStatuses = $model->getCodeArray('TiedStatus',null,'1');
        foreach($tiedStatuses as $key => $tiedStatus){
            $form['default_tied_status']->addMultiOption($key, $tiedStatus);
        }
        
        $this->addElements($form);
        
        $this->addDisplayGroup(
            array('default_currency', 'default_language', 'hierarchy',
                  'linked_data_default' , 'default_collaboration_type' ,
                  'default_flow_type', 'default_finance_type' ,
                  'default_aid_type', 'default_tied_status'),
            'default_field_values',
            array('legend'=>'Default Field Values')
         );
        
        $group = $this->getDisplayGroup('default_field_values');
        $group->setDecorators(array(
            'FormElements',
            'Fieldset',
            array(
                'HtmlTag' ,
                array(
                    'tag' => 'div' ,
                    'class' => 'help activity_defaults-'. $group->getName().' legend-help' ,
                    'placement' => 'PREPEND')
                ),
            array(
                array( 'wrapperAll' => 'HtmlTag' ),
                array( 'tag' => 'div','class'=>'default-activity-list')
            )
        ));                            
        foreach($form as $item_name=>$element)
        {
            $form[$item_name]->addDecorators( array(
                        array('HtmlTag',
                            array(
                                'tag'        =>'<div>',
                                'placement'  =>'PREPEND',
                                'class'      =>'help activity_defaults-'.$item_name
                            )
                        ),
                        array(
                            array( 'wrapperAll' => 'HtmlTag' ),
                            array( 'tag' => 'div','class'=>'clearfix form-item')
                        )
                    )
            );
        }
    }   
}