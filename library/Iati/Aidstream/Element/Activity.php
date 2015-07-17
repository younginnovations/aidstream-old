<?php

class Iati_Aidstream_Element_Activity extends Iati_Core_BaseElement
{

    protected $isMultiple = false;
    protected $className = 'Activity';
    protected $displayName = 'Activity Default';
    protected $xmlName = 'iati-activity';
    protected $tableName = 'iati_activity';
    protected $childElements = array(
                                        'IatiIdentifier' ,
                                        'ReportingOrg' ,
                                        'Title' ,
                                        'Description' ,
                                        'ParticipatingOrg' ,
                                        'OtherActivityIdentifier' ,
                                        'ActivityStatus' ,
                                        'ActivityDate' ,
                                        'ContactInfo' ,
                                        'ActivityScope' ,
                                        'RecipientCountry' ,
                                        'RecipientRegion' ,
                                        'Location' ,
                                        'Sector' ,
                                        'CountryBudgetItems',
                                        'PolicyMarker' ,
                                        'CollaborationType' ,
                                        'DefaultFlowType' ,
                                        'DefaultFinanceType' ,
                                        'DefaultAidType' ,
                                        'DefaultTiedStatus' ,
                                        'Budget' ,
                                        'PlannedDisbursement' ,
                                        'CapitalSpend' ,
                                        'Transaction' ,
                                        'DocumentLink' ,
                                        'RelatedActivity' ,
                                        'LegacyData' ,
                                        'Conditions' ,
                                        'Result'
                                    );
    protected $attribs = array('id' , '@xml_lang', '@default_currency', '@last_updated_datetime', '@linked_data_uri', '@hierarchy');
    protected $iatiAttribs = array('@xml_lang', '@default_currency', '@last_updated_datetime', '@linked_data_uri', '@hierarchy');
    
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
    
     public function save($data , $parentId = null, $duplicate = false)
    { 
        if(!duplicate)
        {
            return parent::save($data, $parentId);
        }
        else
        {
            $elementsData = $this->getElementsData($data);
            $elementsData['@last_updated_datetime'] = date('Y-m-d H:i:s');
            
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
    }
    
}// Ends class