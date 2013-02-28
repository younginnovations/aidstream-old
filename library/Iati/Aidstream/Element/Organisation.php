<?php

class Iati_Aidstream_Element_Organisation extends Iati_Core_BaseElement
{

    protected $isMultiple = false;
    protected $className = 'Organisation';
    protected $displayName = 'Organisation Default';
    protected $tableName = 'iati_organisation';
    protected $childElements = array('ReportingOrg' , 'Identifier' , 'Name' , 'TotalBudget' , 'RecipientOrgBudget' , 'RecipientCountryBudget' , 'DocumentLink');
    protected $attribs = array('id' , '@xml_lang' , '@default_currency' , '@last_updated_datetime' , 'account_id');
    protected $iatiAttribs = array('@xml_lang' , '@default_currency' , '@last_updated_datetime');

    public function getForm($ajax = false)
    {
        $formname = preg_replace('/Element/' , 'Form' , get_class($this));
        if ($this->data)
        {
            $eleForm = new $formname(array('element' => $this));
            $form = $eleForm->getForm();
            $form->prepare();
        } else
        {
            $eleForm = new $formname(array('element' => $this));
            if ($this->count)
            {
                $eleForm->setCount($this->count);
            }
            $form = $eleForm->getFormDefination();
            $form->prepare();
        }
        $form->wrapForm($this->getDisplayName() , $this->getIsRequired());
        return $form;
    }
    
     public function save($data , $parentId = null)
    { 
       
            $elementsData = $this->getElementsData($data);
            $identity = Zend_Auth::getInstance()->getIdentity();
            $elementsData['account_id'] = $identity->account_id;
            
            if($this->hasData($elementsData) ){
                // If no id is present, insert the data else update the data using the id.
                if(!$elementsData['id']){
                    $elementsData['id'] = null;
                    $eleId = $this->db->insert($elementsData);
                } else {
                    $eleId = $elementsData['id'];
                    unset($elementsData['id']);
                    $this->db->update($elementsData , array('id = ?' => $eleId));
                }
            }
       
        return $eleId;
    }
    
}// Ends class