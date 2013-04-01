<?php

class Iati_Aidstream_Form_Activity_ParticipatingOrg extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);  

        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Organisation Name')  
            ->setValue($this->data['text'])
            ->setAttribs(array('rows'=>'2' , 'cols'=> '20'));
        
        $organisationRole = $model->getCodeArray('OrganisationRole', null, '1' , true);
        $form['role'] = new Zend_Form_Element_Select('role');
        $form['role']->setLabel('Organisation Role')  
            ->setValue($this->data['@role'])
            ->setRequired()    
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($organisationRole);
        
        $form['ref'] = new Zend_Form_Element_Text('ref');
        $form['ref']->setLabel('Organisation Identifer')  
            ->setValue($this->data['@ref'])
            ->setAttrib('class' , 'form-text');
        
        $organisationType = $model->getCodeArray('OrganisationType', null, '1' , true);
        $form['type'] = new Zend_Form_Element_Select('type');
        $form['type']->setLabel('Organisation Type')  
            ->setValue($this->data['@type'])
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($organisationType);
            
        $lang = $model->getCodeArray('Language', null, '1' , true);
        $form['xml_lang'] = new Zend_Form_Element_Select('xml_lang');
        $form['xml_lang']->setLabel('Language')
            ->setValue($this->data['@xml_lang'])
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($lang);

        $this->addElements($form);
        return $this;
    }
}