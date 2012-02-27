<?
class Form_Wep_DefaultFieldGroups extends App_Form
{

    public function load($defaults){
        
        $button = new Zend_Form_Element_Button('button');
        $button->setLabel('Check All')
            ->setAttrib('class', 'check-uncheck');
        
        $this->addElement($button);
        
        foreach($defaults['fields'] as $key=>$eachDefault){
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
                                            'Errors',
                                            array('ViewScript', array('viewScript'=>'wep/viewscripts/multicheckboxview.php'))            
                                )
                            )
                        );
        
        
        $this->addDisplayGroup(array('button', 'default_fields',),
                                    'default_field_groups',
                                    array('legend'=>'Default Field Groups')
                                );
        
        $group = $this->getDisplayGroup('default_field_groups');
        $group->setDecorators(array(
            'FormElements',
            'Fieldset',
            array('HtmlTag' , array('tag' => 'div' , 'class' => 'help activity_defaults-'. $group->getName().' legend-help' , 'placement' => 'PREPEND')),
            array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'default-activity-list'))
        ));   
    }
}