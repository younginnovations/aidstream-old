<?php

class Iati_Aidstream_Form_Activity_Transaction_ProviderOrg_Narrative extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();
        $identity = Zend_Auth::getInstance()->getIdentity();
        if($identity->account_id) {
            $defaultFieldsValues = $model->getDefaults('default_field_values', 'account_id', $identity->account_id);
            $defaults = $defaultFieldsValues->getDefaultFields();
        }
        
        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Title')
            ->setRequired()    
            ->setValue($this->data['text'])
            ->setAttribs(array('rows'=>'3' , 'cols'=> '20'))
            ->setDescription('Use your reporting organisation info? <a class="use-reporting-org" reporting-org-name="' . $defaults['reporting_org'] . '" reporting-org-ref="' . $defaults['reporting_org_ref'] .'">Click here.</a>')
            ->addDecorators(array(array('HtmlTag' , array('tag' => 'div' , 'class' => 'help transaction-provider_org-text' , 'placement' => 'PREPEND')), array('Description' , array('escape' => false , 'class' => 'description'))));

            
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