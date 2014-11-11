<?php

class Iati_Aidstream_Form_Activity_Transaction_ProviderOrg extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        $model = new Model_Wep();
        if($identity->account_id) {
            $defaultFieldsValues = $model->getDefaults('default_field_values', 'account_id', $identity->account_id);
            $defaults = $defaultFieldsValues->getDefaultFields();
        }

        $this->setAttrib('class' , 'first-child')
            ->setMethod('post')
            ->setIsArray(true);

        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
       
        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')
            ->setRequired()
            ->setValue($this->data['text'])
            ->setAttribs(array('rows'=>'3' , 'cols'=> '20'))
            ->setDescription('Use your reporting organisation info? <a class="use-reporting-org" reporting-org-name="' . $defaults['reporting_org'] . '" reporting-org-ref="' . $defaults['reporting_org_ref'] .'">Click here.</a>')
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-provider_org-text' , 'placement' => 'PREPEND')), array('Description' , array('escape' => false , 'class' => 'description'))));

        $form['ref'] = new Zend_Form_Element_Text('ref');
        $form['ref']->setLabel('Organisation Identifier Code')
            ->setValue($this->data['@ref'])
            ->setAttrib('class' , 'form-text')
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-provider_org-ref' , 'placement' => 'PREPEND'))));

        $form['provider_activity_id'] = new Zend_Form_Element_Text('provider_activity_id');
        $form['provider_activity_id']->setLabel('Provider Activity Id')
            ->setValue($this->data['@provider_activity_id'])
            ->setAttrib('class' , 'form-text')
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-provider_org-provider_activity_id' , 'placement' => 'PREPEND'))));

        $this->addElements($form);
        return $this;
    }
}