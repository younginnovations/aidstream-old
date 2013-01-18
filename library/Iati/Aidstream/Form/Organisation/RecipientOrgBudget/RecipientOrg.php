<?php

class Iati_Aidstream_Form_Organisation_RecipientOrgBudget_RecipientOrg extends Iati_Core_BaseForm
{
    public function getFormDefination()
    {
        $model = new Model_Wep();

        $this->setAttrib('class' , 'first-child')
            ->setMethod('post')
            ->setIsArray(true);

        $form = array();

        $form['id'] = new Zend_Form_Element_Hidden('id');
        $form['id']->setValue($this->data['id']);
        
        $form['ref'] = new Zend_Form_Element_Text('code');
        $form['ref']->setLabel('Ref')
            ->setValue($this->data['@ref'])
            ->setRequired()
            ->setAttrib('class' , 'form-text');

        $form['text'] = new Zend_Form_Element_Textarea('text');
        $form['text']->setLabel('Text')
            ->setValue($this->data['text'])
            ->setAttribs(array('rows'=>'3' , 'cols'=> '20'));
            
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