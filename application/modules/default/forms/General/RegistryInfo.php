<?php

class Form_General_RegistryInfo extends App_Form
{
    public function init()
    {
        $form['publisher_id'] = new Zend_Form_Element_Text('publisher_id');
        $form['publisher_id']->setLabel('Publisher Id')
            ->setAttrib('class', 'form-text')
            ->addDecorators(array(array(
                                        'HtmlTag',
                                        array(
                                              'tag'        =>'<div>',
                                              'placement'  =>'APPEND',
                                              'class'      =>'help activity_defaults-publisher_id'
                                          )
                            )))
            ->addErrorMessage('Please Enter the Publisher ID');
        
        $form['api_key'] = new Zend_Form_Element_Text('api_key');
        $form['api_key']->setLabel('API Key')
            ->setAttrib('class', 'form-text')
            ->addDecorators(array(array(
                                        'HtmlTag',
                                        array(
                                              'tag'        =>'<div>',
                                              'placement'  =>'APPEND',
                                              'class'      =>'help activity_defaults-api_key'
                                          )
                            )))
            ->addErrorMessage('Please Enter an API key');
            
        $form['update_registry'] = new Zend_Form_Element_Radio('update_registry');
        $form['update_registry']->setLabel('Automatically Update the IATI Registry when publishing files')
            ->setValue(0)
            ->addDecorators(array(array(
                                        'HtmlTag',
                                        array(
                                              'tag'        =>'<div>',
                                              'placement'  =>'APPEND',
                                              'class'      =>'help activity_defaults-update_registry'
                                          )
                            )))
            ->addMultiOptions(array('No' , 'Yes'));
        
        $form['publishing_type'] = new Zend_Form_Element_Radio('publishing_type');
        $form['publishing_type']->setLabel('Publishing Type')
            ->setRequired()
            ->setValue(0)
            ->addMultiOptions(array('Unsegmented' , 'Segmented'));
            
            
        foreach($form as $item_name=>$element)
        {
            if($item_name == "update_registry"){
                $form[$item_name]->addDecorators( array(
                        array(array( 'wrapperAll' => 'HtmlTag'), array('tag' => 'div' , 'class'=>'clearfix form-item update-registry-element'))
                    )
                );
            } else {
                $form[$item_name]->addDecorators( array(
                        array(array( 'wrapperAll' => 'HtmlTag'), array('tag' => 'div' , 'class'=>'clearfix form-item'))
                    )
                );
            }
        }
            
        $this->addElements($form);
        
        $this->addDisplayGroup( array('publishing_type'),
                                'publishing_info',
                                array('legend' => 'Publishing type',
                                )
                            );
        
        $this->addDisplayGroup(
                                array('publisher_id' , 'api_key' , 'update_registry'),
                                'registry_info',
                                array('legend' => 'Registry Info')
                            );
        
        $registry_info = $this->getDisplayGroup('registry_info');
        $registry_info->setDecorators(array(
            'FormElements',
            'Fieldset',
            array('HtmlTag' , array('tag' => 'div' , 'class' => 'help activity_defaults-registry_info legend-help' , 'placement' => 'PREPEND')),
            array(array( 'wrapperAll' => 'HtmlTag'), array( 'tag' => 'div','class'=>'default-activity-list'))
        ));
        
        $publishing_info = $this->getDisplayGroup('publishing_info');
        $publishing_info_legend = $publishing_info->getLegend();
        $publishing_info->setDecorators(array(
            'FormElements',
            'Fieldset',
            array('HtmlTag' , array('tag' => 'div' , 'class' => 'help activity_defaults-publishing_type legend-help' , 'placement' => 'PREPEND')),
            array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'default-activity-list'))
        ));
    }
}