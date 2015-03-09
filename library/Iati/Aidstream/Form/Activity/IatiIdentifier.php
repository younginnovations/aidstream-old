<?php

class Iati_Aidstream_Form_Activity_IatiIdentifier extends Iati_Core_BaseForm
{ 

    public function getFormDefination()
    {  
        //Fetch reporting org
        $reportingOrgObj = new Iati_Aidstream_Element_Activity_ReportingOrg();
        $reportingOrg = $reportingOrgObj->fetchData($this->data['activity_id'],true);
        $reportingOrgText = $reportingOrg['@ref'];
        $form = array();

        $activity_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('activity_id');
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $clause = $db->quoteInto('activity_id != ?', $activity_id);

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);

        $form['reporting_org'] = new Zend_Form_Element_Hidden('reporting_org');
        $form['reporting_org']->setValue($reportingOrgText)
                ->setAttribs(array('class' => 'hidden-field'));
        
        $form['activity_identifier'] = new Zend_Form_Element_Text('activity_identifier');
        $form['activity_identifier']->setLabel('Activity Identifier')
                ->setValue($this->data['activity_identifier'])
                ->setRequired()
                ->addValidator('Db_NoRecordExists', false, 
                    array('table' => 'iati_identifier', 'field' => 'activity_identifier', 'exclude' => $clause,
                        'messages' => array(
                            Zend_Validate_Db_NoRecordExists::ERROR_RECORD_FOUND => 'Activity identifier already in use.'
                        )))
                ->setAttribs(array('class' => 'form-text'))
                ->setAttrib('cols', '40')
                ->setAttrib('rows', '2');
        
        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('IATI Activity Identifier')
                ->setValue($this->data['text'])
                ->setRequired()
                ->setAttrib('cols', '40')
                ->setAttrib('rows', '2')
                ->setAttribs(array('readonly' => 'True'));
                
        
        $this->addElements($form);
        return $this;

    }
}