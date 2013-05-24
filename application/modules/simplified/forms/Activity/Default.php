<?php
class Simplified_Form_Activity_Default extends Iati_SimplifiedForm
{
    protected $data;
    public function init()
    {
        $model = new Model_Wep();
        
        $this->setAttrib('id' , 'simplified-default-form')
            ->setIsArray(true);
        $form = array();
        
        $form['activity_id'] = new Zend_Form_Element_Hidden('activity_id');
        $form['activity_id']->setValue($this->data['activity_id']);

        $form['identifier_id'] = new Zend_Form_Element_Hidden('identifier_id');
        $form['identifier_id']->setValue($this->data['identifier_id']);
        
        $form['identifier'] = new Zend_Form_Element_Text('identifier');
        $form['identifier']->setLabel('Project Identifier')
            ->setRequired()
            ->setValue($this->data['identifier'])
            ->setAttrib('class', 'form-text');
            
        $form['title_id'] = new Zend_Form_Element_Hidden('title_id');
        $form['title_id']->setValue($this->data['title_id']);
        
        $form['title'] = new Zend_Form_Element_Text('title');
        $form['title']->setLabel('Title')
            ->setRequired()
            ->setValue($this->data['title'])
            ->setAttrib('class', 'form-text');
            
        $form['description_id'] = new Zend_Form_Element_Hidden('description_id');
        $form['description_id']->setValue($this->data['description_id']);
        
        $form['description'] = new Zend_Form_Element_Textarea('description');
        $form['description']->setLabel('Description')
            ->setRequired()
            ->setValue($this->data['description'])
            ->setAttrib('COLS', '40')
            ->setAttrib('ROWS', '4')
            ->setAttrib('class', 'form-text');
          
        $fundingOrgData = $this->data['funding_org'];
        $fundingOrgs = '';

        if($fundingOrgData && is_array($fundingOrgData)){
            $fundingOrgs = implode(',' , $fundingOrgData);
        }
        $form['funding_org'] = new Zend_Form_Element_Hidden('funding_org');
        $form['funding_org']->setValue($fundingOrgs)
            ->setLabel('Funding Organisation')
            ->setAttrib('style' , 'width:300px');
        
        /*
        $form['funding_org'] = new Zend_Form_Element_Text('funding_org');
        $form['funding_org']->setLabel('Funding Organisation')
            ->setRequired()
            ->setValue(array('test'))
            ->setAttrib('class', 'form-text');
        */   
        $form['start_date_id'] = new Zend_Form_Element_Hidden('start_date_id');
        $form['start_date_id']->setValue($this->data['start_date_id']);

        $form['start_date'] = new Zend_Form_Element_Text('start_date');
        $form['start_date']->setLabel('Actual Start Date')
            ->setRequired()
            ->setValue($this->data['start_date'])
            ->setAttrib('class', 'form-text datepicker');
            
        $form['end_date_id'] = new Zend_Form_Element_Hidden('end_date_id');
        $form['end_date_id']->setValue($this->data['end_date_id']);

        $form['end_date'] = new Zend_Form_Element_Text('end_date');
        $form['end_date']->setLabel('Actual End Date')
            ->setRequired()
            ->setValue($this->data['end_date'])
            ->addValidator(new App_Validate_EndDate($form['start_date']))
            ->setAttrib('class', 'form-text datepicker');

            
        $sectorCodes = $model->getCodeArray('Sector' , '' , 1);
        $sectors = $this->data['sector'];
        if($sectors){
            foreach($sectors as $sector){
                $sectorData[] = $sector['sector'];
            }
        }
        $form['sector'] = new Zend_Form_Element_Select('sector');
        $form['sector']->setLabel('Sector')
            ->addMultiOptions($sectorCodes)
            ->setRegisterInArrayValidator(false)
            ->setValue($sectorData)
            ->setAttrib('multiple', 'true')
            ->setAttrib('class', 'form-text');
        
        $this->addElements($form);
        
        // location
        $locationForm = new App_Form();
        $locationForm->removeDecorator('form');
        if($this->data['location']){
            foreach($this->data['location'] as $key=>$locationData){
                $location = new Simplified_Form_Activity_Location(array('data' => $locationData , 'count' => $key));
                $locationForm->addSubForm($location , 'location'.$key);
                $location->removeDecorator('form');
            }
            
        } else {
            $location = new Simplified_Form_Activity_Location(array('data' => $locationData));
            $locationForm->addSubForm($location , 'location');
            $location->removeDecorator('form');
        }
        $add = new Iati_Form_Element_Note('add');
        $add->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'simplified-add-more'));
        $add->setValue("<a href='#' class='button' value='Location'> Add More</a>");
        $locationForm->addElement($add);
        $this->addSubForm($locationForm , 'location_wrapper');
        
        
        // Budget
        $budgetForm = new App_Form();
        $budgetForm->removeDecorator('form');
        if($this->data['budget']){
            foreach($this->data['budget'] as $key=>$budgetData){
                $budget = new Simplified_Form_Activity_Budget(array('data' => $budgetData , 'count' => $key));
                $budgetForm->addSubForm($budget , 'budget'.$key);
                $budget->removeDecorator('form');
            }
            
        } else {
            $budget = new Simplified_Form_Activity_Budget(array('data' => $budgetData));
            $budgetForm->addSubForm($budget , 'budget');
            $budget->removeDecorator('form');
        }
        $add = new Iati_Form_Element_Note('add');
        $add->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'simplified-add-more'));
        $add->setValue("<a href='#' class='button' value='Budget'> Add More</a>");
        $budgetForm->addElement($add);
        $this->addSubForm($budgetForm , 'budget_wrapper');
        
        /**
         * @deprecated
         */
        /*
        // Commitment
        $commForm = new App_Form();
        $commForm->removeDecorator('form');
        if($this->data['commitment']){
            foreach($this->data['commitment'] as $key=>$commitmentData){
                $commitment = new Simplified_Form_Activity_Transaction_Commitment(array('data' => $commitmentData , 'count' => $key));
                $commForm->addSubForm($commitment , 'commitment'.$key);
                $commitment->removeDecorator('form');
            }
        } else {
            $commitment = new Simplified_Form_Activity_Transaction_Commitment();
            $commForm->addSubForm($commitment , 'commitment');
            $commitment->removeDecorator('form');
        }
        $add = new Iati_Form_Element_Note('add');
        $add->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'simplified-add-more'));
        $add->setValue("<a href='#' class='button' value='Transaction_Commitment'> Add More</a>");
        $commForm->addElement($add);
        $this->addSubForm($commForm , 'commitment_wrapper');
        */
        
        // incommingFund
        $incommForm = new App_Form();
        $incommForm->removeDecorator('form');
        if($this->data['incommingFund']){
            foreach($this->data['incommingFund'] as $key=>$incommingFundData){
                $incommingFund = new Simplified_Form_Activity_Transaction_IncommingFund(array('data' => $incommingFundData , 'count' => $key));
                $incommForm->addSubForm($incommingFund , 'incommingFund'.$key);
                $incommingFund->removeDecorator('form');
            }
        } else {
            $incommingFund = new Simplified_Form_Activity_Transaction_IncommingFund();
            $incommForm->addSubForm($incommingFund , 'incommingFund');
            $incommingFund->removeDecorator('form');
        }
        $add = new Iati_Form_Element_Note('add');
        $add->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'simplified-add-more'));
        $add->setValue("<a href='#' class='button' value='Transaction_IncommingFund'> Add More</a>");
        $incommForm->addElement($add);
        $this->addSubForm($incommForm , 'incomming_fund_wrapper');
        
        // Expenditure
        $expForm = new App_Form();
        $expForm->removeDecorator('form');
        if($this->data['expenditure']){
            foreach($this->data['expenditure'] as $key=>$expenditureData){
                $expenditure = new Simplified_Form_Activity_Transaction_Expenditure(array('data' => $expenditureData , 'count' => $key));
                $expForm->addSubForm($expenditure , 'expenditure'.$key);
                $expenditure->removeDecorator('form');
            }
        } else {
            $expenditure = new Simplified_Form_Activity_Transaction_Expenditure();
            $expForm->addSubForm($expenditure , 'expenditure');
            $expenditure->removeDecorator('form');
        }
        $add = new Iati_Form_Element_Note('add');
        $add->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'simplified-add-more'));
        $add->setValue("<a href='#' class='button' value='Transaction_Expenditure'> Add More</a>");
        $expForm->addElement($add);
        $this->addSubForm($expForm , 'expenditure_wrapper');
        
        // document
        $documentForm = new App_Form();
        $documentForm->removeDecorator('form');
        if($this->data['document']){
            foreach($this->data['document'] as $key=>$documentData){
                $document = new Simplified_Form_Activity_Document(array('data' => $documentData , 'count' => $key));
                $documentForm->addSubForm($document , 'document'.$key);
                $document->removeDecorator('form');
            }
            
        } else {
            $document = new Simplified_Form_Activity_Document(array('data' => $documentData));
            $documentForm->addSubForm($document , 'document');
            $document->removeDecorator('form');
        }
        $add = new Iati_Form_Element_Note('add');
        $add->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'simplified-add-more'));
        $add->setValue("<a href='#' class='button' value='Document'> Add More</a>");
        $documentForm->addElement($add);
        $this->addSubForm($documentForm , 'document_wrapper');
        
        
        // Result
        $resultForm = new App_Form();
        $resultForm->removeDecorator('form');
        if($this->data['result']){
            foreach($this->data['result'] as $key=>$resultData){
                $result = new Simplified_Form_Activity_Result(array('data' => $resultData , 'count' => $key));
                $resultForm->addSubForm($result , 'result'.$key);
                $result->removeDecorator('form');
            }
            
        } else {
            $result = new Simplified_Form_Activity_Result(array('data' => $resultData));
            $resultForm->addSubForm($result , 'result');
            $result->removeDecorator('form');
        }
        $add = new Iati_Form_Element_Note('add');
        $add->addDecorator('HtmlTag', array('tag' => 'span' , 'class' => 'simplified-add-more'));
        $add->setValue("<a href='#' class='button' value='Result'> Add More</a>");
        $resultForm->addElement($add);
        $this->addSubForm($resultForm , 'result_wrapper');
        
        
        foreach($form as $item_name=>$element)
        {
            $form[$item_name]->addDecorators( array(
                        array(array( 'wrapperAll' => 'HtmlTag' ), array( 'tag' => 'div','class'=>'clearfix form-item'))
                    )
            );
        }
        
        $this->addElement('submit' , 'save',
            array(
                'label'    => 'Save',
                'required' => false,
            )
        );
        
        $this->addDecorators(array(
            array('ViewScript', array('viewScript' => 'default/viewscripts/simplified.phtml'))
        ));
    }
    
    public function setData($data)
    {
        $this->data = $data;
    }
}
