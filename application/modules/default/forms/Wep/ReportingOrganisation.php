<?php
class Form_Wep_ReportingOrganisation extends App_Form
{

    public function load($defaults){
        $form = array();
        $model = new Model_Wep();

        $form['default_reporting_org'] = new Zend_Form_Element_Text('default_reporting_org');
        $form['default_reporting_org']->setLabel('Reporting Organisation Name')
            ->setValue($defaults['field_values']['reporting_org'])
            ->setRequired()
            ->setAttrib('class', 'form-text');

        $form['reporting_org_ref'] = new Zend_Form_Element_Text('reporting_org_ref');
        $form['reporting_org_ref']->setLabel('Reporting Organisation Identifier')
            ->setRequired()
            ->setValue($defaults['field_values']['reporting_org_ref'])
            ->setAttrib('width','20px')
            ->setAttrib('class', 'form-text');
            
        $reportingOrgType = $model->getCodeArray('OrganisationType',null,'1');
        $form['reporting_org_type'] = new Zend_Form_Element_Select('reporting_org_type');
        $form['reporting_org_type']->setLabel('Reporting Organisation Type')
            ->setRequired()
            ->setValue($defaults['field_values']['reporting_org_type'])
            ->addMultiOption('','Select anyone')
            ->addMultiOptions($reportingOrgType)
            ->setAttrib('width','20px')
            ->setAttrib('class', 'form-select');
            
        $language = $model->getCodeArray('Language',null,'1');
        $form['reporting_org_lang'] = new Zend_Form_Element_Select('reporting_org_lang');
        $form['reporting_org_lang']->setLabel('Reporting Organisation Language')
            ->addMultiOption('', 'Select anyone')->setValue($defaults['field_values']['reporting_org_lang'])
            ->setAttrib('class', 'form-select')
            ->addMultiOptions($language);
            
        $this->addElements($form);
        $this->addDisplayGroup(
            array( 'reporting_org_ref', 'reporting_org_type' ,
                  'default_reporting_org' , 'reporting_org_lang'),
            'reporting_org_info',
            array('legend' =>'Reporting Organisation Info')
         );
        $group = $this->getDisplayGroup('reporting_org_info');
        $group->setDecorators(array(
            'FormElements',
            'Fieldset',
            array(
                'HtmlTag' ,
                array(
                    'tag' => 'div' ,
                    'class' => 'help activity_defaults-'. $group->getName().' legend-help' ,
                    'placement' => 'PREPEND'
                )
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