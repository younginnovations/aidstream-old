<?php
class Form_Wep_Title extends App_Form
{
    public function add($state = "add", $activity_id = '', $account_id = ""){
        $this->setName('iati_title');
        $form = array();        $model = new Model_Viewcode();
        $language = $model->getCode('Language',null,'1');
        $rowSet = $model->getRowsByFields('default_field_values', 'account_id', $account_id);
        $defaultValues = unserialize($rowSet[0]['object']);
        $default = $defaultValues->getDefaultFields();
        $form['iati_title_text'] = new Zend_Form_Element_Text('iati_title_text');
        $form['iati_title_text']->setLabel('Name')->setRequired();
        
        $form["iati_title_xmllang"] = new Zend_Form_Element_Select("iati_title_xmllang");
        $form['iati_title_xmllang']->setLabel('Language')->addMultiOption('', 'Select one of the following option:');
      $form['iati_title_xmllang']->setValue($default['language']);
//        foreach($language as $eachL){
        foreach($language[0] as $eachLanguage){
            $form['iati_title_xmllang']->addMultiOption($eachLanguage['id'], $eachLanguage['Code']);
        }
//        }
        
        $form['activity_id'] = new Zend_Form_Element_Hidden('activity_id');
        $form['activity_id']->setValue($activity_id)->setAttrib('class','hidden-field');
         
        $form['table'] = new Zend_Form_Element_Hidden('table');
        $form['table']->setValue('iati_title')->setAttrib('class','hidden-field');
        
        $form['form_name'] = new Zend_Form_Element_Hidden('form_name');
        $form['form_name']->setValue('Title')->setAttrib('class','hidden-field');
        
        $form['save'] = new Zend_Form_Element_Submit('Save');
        $form['save']->setValue('save')->setAttrib('class','ajax_save');
        $this->addElements($form); 
//        $this->addElement($save);                            
        $this->addDisplayGroup(
            array('iati_title_text', 'iati_title_xmllang', 'save'),
            'field3',
            array('legend'=>'Title')
        );

       
        
        $this->setMethod('post');
    }
}