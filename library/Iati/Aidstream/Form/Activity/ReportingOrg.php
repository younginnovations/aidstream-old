<?php

class Iati_Aidstream_Form_Activity_ReportingOrg extends Iati_Core_BaseForm
{

    public function getFormDefination()
    {
        $baseurl = Zend_Controller_Front::getInstance()->getBaseUrl();
        $model = new Model_Wep();
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);

        $form['@ref'] = new Zend_Form_Element_Text('@ref');
        $form['@ref']->setLabel('Organisation Identifier')
                ->setValue($this->data['@ref'])
                ->setRequired()
                ->setAttrib('class' , 'form-text')
                ->setAttribs(array('disabled' => 'disabled'));
        
        $reportingOrgType = $model->getRowsByFields('OrganisationType','id', $this->data['@type']);
        $form['type'] = new Zend_Form_Element_Select('type');
        $form['type']->setLabel('Organisation Type')
                ->setAttrib('class' , 'form-select')
                ->addMultiOptions(array($this->data['@type']=>$reportingOrgType[0]['Name']))
                ->setAttribs(array('disabled' => 'disabled'));

        $this->addElements($form);
        $this->setAction($baseurl . '/wep/update-reporting-org/?id='.$this->data['id'].'&activity_id='.$this->data['activity_id']);
        return $this;

    }

    public function addSubmitButton($label , $saveAndViewlabel = 'Save and View')
    {
        $this->addElement('submit' , 'update' , array(
            'label' => 'Update' ,
            'required' => false ,
            'ignore' => false ,
                )
        );
        $this->removeElement('update');

    }

}