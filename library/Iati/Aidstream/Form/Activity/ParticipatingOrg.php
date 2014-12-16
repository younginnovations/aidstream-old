<?php

class Iati_Aidstream_Form_Activity_ParticipatingOrg extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);  
        
        $organisationRole = $model->getCodeArray('OrganisationRole', null, '1' , true);
        $form['role'] = new Zend_Form_Element_Select('role');
        $form['role']->setLabel('Organisation Role')  
            ->setValue($this->data['@role'])
            ->setRequired()    
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($organisationRole);
        
        $form['ref'] = new Zend_Form_Element_Text('ref');
        $form['ref']->setLabel('Organisation Identifer')
            ->setRequired()  
            ->setValue($this->data['@ref'])
            ->setAttrib('class' , 'form-text');
        
        $organisationType = $model->getCodeArray('OrganisationType', null, '1' , true);
        $form['type'] = new Zend_Form_Element_Select('type');
        $form['type']->setLabel('Organisation Type')  
            ->setValue($this->data['@type'])
            ->setAttrib('class' , 'form-select')
            ->setMultioptions($organisationType);

        $this->addElements($form);
        return $this;
    }
}