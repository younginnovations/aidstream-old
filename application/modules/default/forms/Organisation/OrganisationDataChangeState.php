<?php
class Form_Organisation_OrganisationDataChangeState extends App_Form
{
    public function init()
    {
        $this->setName('iati_organisationdata_change_status');
        $this->setMethod('post');
        $form = array();

        $form['status'] = new Zend_Form_Element_Hidden('status');

        $form['ids'] = new Zend_Form_Element_Hidden('ids');

        $form['change_state'] = new Zend_Form_Element_Submit('change_state');
        $form['change_state']->class = 'form-submit';

        $this->addElements($form);
    }
}